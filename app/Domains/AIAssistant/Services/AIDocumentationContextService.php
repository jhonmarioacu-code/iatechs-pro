<?php

declare(strict_types=1);

namespace App\Domains\AIAssistant\Services;

class AIDocumentationContextService
{
    /**
     * @var array<string, string>|null
     */
    protected static ?array $cache = null;

    public function getContextByRole(string $role): string
    {
        $docs = $this->loadDocs();

        $base = [
            'Reglas IAtechs Pro:',
            '- Responde de forma accionable, breve y profesional.',
            '- No inventes procesos; sigue la documentacion oficial del proyecto.',
            '- Si falta informacion, indicalo claramente y sugiere siguiente paso.',
            '',
            'Resumen de roles y permisos:',
            $docs['roles'],
            '',
            'Resumen de flujos de negocio:',
            $docs['flows'],
            '',
            'Resumen del modulo AI Assistant:',
            $docs['ai_module'],
        ];

        $roleAdvice = match ($role) {
            'super_admin' => 'Prioriza gobierno de plataforma, seguridad, observabilidad y operaciones globales.',
            'owner', 'administrator', 'manager' => 'Prioriza operaciones de empresa, control de tickets, productividad y cumplimiento de procesos.',
            'technician' => 'Prioriza flujo tecnico: ticket -> diagnostico -> cotizacion -> reparacion -> cierre.',
            'customer' => 'Prioriza estado de ticket/factura/cotizacion y pasos de aprobacion o pago.',
            default => 'Prioriza resolver la consulta con base en rol y permisos disponibles.',
        };

        $base[] = '';
        $base[] = 'Enfoque por rol actual: '.$roleAdvice;

        return implode("\n", $base);
    }

    /**
     * @return array<string, string>
     */
    protected function loadDocs(): array
    {
        if (self::$cache !== null) {
            return self::$cache;
        }

        self::$cache = [
            'roles' => $this->readAndTrim(base_path('docs/05-Roles-Permisos.md')),
            'flows' => $this->readAndTrim(base_path('docs/06-Flujos-Negocio.md')),
            'ai_module' => $this->readAndTrim(base_path('docs/modules/29-AI-Assistant.md')),
        ];

        return self::$cache;
    }

    protected function readAndTrim(string $path): string
    {
        if (!is_file($path)) {
            return 'Documento no disponible.';
        }

        $content = (string) file_get_contents($path);
        $content = trim(preg_replace('/\s+/', ' ', $content) ?? '');

        return mb_substr($content, 0, 3500);
    }
}
