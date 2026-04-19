<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class OpenAITicketAnalyzer
{
    public function analyze(string $filePath): array
    {
        $fullPath = Storage::disk('public')->path($filePath);

        if (! is_readable($fullPath)) {
            throw new \Exception('No se puede leer el archivo adjunto en disco.');
        }

        $mime = mime_content_type($fullPath) ?: 'application/octet-stream';

        if (str_starts_with($mime, 'image/')) {
            return $this->analyzeImageWithVision($fullPath, $mime);
        }

        return $this->analyzeDocumentWithResponsesApi($fullPath);
    }

    /**
     * Imágenes: la API Responses con input_file no admite .jpg/.png (solo documentos).
     * Usamos Chat Completions con visión.
     */
    private function analyzeImageWithVision(string $fullPath, string $mime): array
    {
        $key = $this->openAiApiKey();

        $dataUrl = 'data:'.$mime.';base64,'.base64_encode((string) file_get_contents($fullPath));

        $response = Http::withToken($key)
            ->timeout(120)
            ->post('https://api.openai.com/v1/chat/completions', [
                'model' => config('services.openai.model'),
                'messages' => [
                    [
                        'role' => 'user',
                        'content' => [
                            [
                                'type' => 'text',
                                'text' => $this->analysisPrompt(),
                            ],
                            [
                                'type' => 'image_url',
                                'image_url' => [
                                    'url' => $dataUrl,
                                ],
                            ],
                        ],
                    ],
                ],
                'response_format' => [
                    'type' => 'json_schema',
                    'json_schema' => [
                        'name' => 'ticket_analysis',
                        'schema' => $this->analysisJsonSchema(),
                        'strict' => true,
                    ],
                ],
            ]);

        if (! $response->successful()) {
            throw new \Exception('Error en Chat Completions (imagen): '.$response->body());
        }

        $text = data_get($response->json(), 'choices.0.message.content');

        return $this->decodeAnalysisJson($text);
    }

    /**
     * PDF y otros documentos admitidos por Responses + input_file.
     */
    private function analyzeDocumentWithResponsesApi(string $fullPath): array
    {
        $key = $this->openAiApiKey();

        $uploadResponse = Http::withToken($key)
            ->attach('file', (string) file_get_contents($fullPath), basename($fullPath))
            ->post('https://api.openai.com/v1/files', [
                'purpose' => 'user_data',
            ]);

        if (! $uploadResponse->successful()) {
            throw new \Exception('Error subiendo archivo a OpenAI: '.$uploadResponse->body());
        }

        $fileId = $uploadResponse->json('id');

        $response = Http::withToken($key)
            ->timeout(120)
            ->post('https://api.openai.com/v1/responses', [
                'model' => config('services.openai.model'),
                'input' => [
                    [
                        'role' => 'user',
                        'content' => [
                            [
                                'type' => 'input_text',
                                'text' => $this->analysisPrompt(),
                            ],
                            [
                                'type' => 'input_file',
                                'file_id' => $fileId,
                            ],
                        ],
                    ],
                ],
                'text' => [
                    'format' => [
                        'type' => 'json_schema',
                        'name' => 'ticket_analysis',
                        'schema' => $this->analysisJsonSchema(),
                        'strict' => true,
                    ],
                ],
            ]);

        if (! $response->successful()) {
            throw new \Exception('Error en Responses API: '.$response->body());
        }

        $text = data_get($response->json(), 'output.0.content.0.text');

        if (! $text) {
            $text = $this->extractStructuredTextFromResponsesOutput($response->json());
        }

        return $this->decodeAnalysisJson($text);
    }

    /**
     * @param  array<string, mixed>|null  $json
     */
    private function extractStructuredTextFromResponsesOutput(?array $json): ?string
    {
        if ($json === null) {
            return null;
        }

        $output = $json['output'] ?? [];

        foreach ($output as $item) {
            $content = $item['content'] ?? [];

            foreach ($content as $block) {
                if (($block['type'] ?? '') === 'output_text' && isset($block['text'])) {
                    return $block['text'];
                }
                if (isset($block['text'])) {
                    return $block['text'];
                }
            }
        }

        return null;
    }

    /**
     * @return array<string, mixed>
     */
    private function decodeAnalysisJson(?string $text): array
    {
        if ($text === null || $text === '') {
            throw new \Exception('No se recibió salida estructurada del modelo.');
        }

        $decoded = json_decode($text, true);

        if (! is_array($decoded)) {
            throw new \Exception('La respuesta JSON del modelo no es válida.');
        }

        return $decoded;
    }

    private function openAiApiKey(): string
    {
        $key = config('services.openai.api_key');

        if ($key === null || $key === '') {
            throw new \Exception(
                'OPENAI_API_KEY no está configurada. Añádela en .env (sin comillas) y ejecuta php artisan config:clear.'
            );
        }

        return trim((string) $key, " \t\n\r\0\x0B\"'");
    }

    /**
     * @return array<string, mixed>
     */
    private function analysisJsonSchema(): array
    {
        return [
            'type' => 'object',
            'properties' => [
                'technical_description' => ['type' => 'string'],
                'ocr_text' => ['type' => 'string'],
                'suggested_category' => ['type' => 'string'],
                'possible_causes' => [
                    'type' => 'array',
                    'items' => ['type' => 'string'],
                ],
                'executive_summary' => ['type' => 'string'],
                'what_the_image_says' => ['type' => 'string'],
                'confidence' => ['type' => 'number'],
                'needs_human_review' => ['type' => 'boolean'],
            ],
            'required' => [
                'technical_description',
                'ocr_text',
                'suggested_category',
                'possible_causes',
                'executive_summary',
                'what_the_image_says',
                'confidence',
                'needs_human_review',
            ],
            'additionalProperties' => false,
        ];
    }

    private function analysisPrompt(): string
    {
        return <<<'TXT'
Analiza el archivo adjunto de un ticket de soporte.
1. Describe técnicamente el problema observado.
2. Extrae todo el texto visible del archivo.
3. Sugiere una categoría del ticket.
4. Propón posibles causas.
5. Genera un resumen ejecutivo breve.
6. Incluye explícitamente qué dice la imagen o documento.
7. Si no hay suficiente información, indícalo.
8. No inventes información.
TXT;
    }
}
