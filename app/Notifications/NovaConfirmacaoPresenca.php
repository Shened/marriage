<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class NovaConfirmacaoPresenca extends Notification
{
    use Queueable;

    protected $confirmacao;

    public function __construct($confirmacao)
    {
        $this->confirmacao = $confirmacao;
    }

    /**
     * Define os canais de notificação
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Cria o email
     */
    public function toMail($notifiable)
    {
        // Verificar se confirmou ou não
        $presencaStatus = $this->confirmacao['presenca'] === 'sim' 
            ? '✅ CONFIRMOU PRESENÇA' 
            : '❌ NÃO PODERÁ COMPARECER';

        $presencaColor = $this->confirmacao['presenca'] === 'sim' 
            ? '#4A7C59'  // Verde (sua paleta)
            : '#C85A54'; // Vermelho

        // Informações do acompanhante
        $infoAcompanhante = '';
        if ($this->confirmacao['temParceiro']) {
            $infoAcompanhante = "\n**Acompanhante:** " . 
                $this->confirmacao['parceiro']['nome'] . 
                " (Idade: " . $this->confirmacao['parceiro']['idade'] . " anos)";
        }

        // Informações dos filhos
        $infoFilhos = '';
        if ($this->confirmacao['temFilhos'] && !empty($this->confirmacao['filhos'])) {
            $infoFilhos = "\n**Filhos:** " . count($this->confirmacao['filhos']) . " criança(s)";
            foreach ($this->confirmacao['filhos'] as $index => $filho) {
                $infoFilhos .= "\n  • " . $filho['nome'] . " (" . $filho['idade'] . " anos)";
            }
        }

        // Informações de restrições
        $infoRestricoes = '';
        if ($this->confirmacao['temRestricoes'] && !empty($this->confirmacao['restricoes'])) {
            $infoRestricoes = "\n**⚠️ Restrições Alimentares:**\n" . $this->confirmacao['restricoes'];
        }

        return (new MailMessage)
            ->subject('🎉 Nova Confirmação - ' . $this->confirmacao['nome'] . ' ' . $this->confirmacao['apelido'])
            ->greeting('Nova Confirmação Recebida!')
            ->line('Alguém acabou de preencher o formulário de confirmação de presença.')
            ->line('---')
            ->line('**👤 Dados Pessoais:**')
            ->line('**Nome Completo:** ' . $this->confirmacao['nome'] . ' ' . $this->confirmacao['apelido'])
            ->line('**Idade:** ' . $this->confirmacao['idade'] . ' anos')
            ->line('**Telefone:** ' . $this->confirmacao['telefone'])
            ->line('---')
            ->line('**📋 Confirmação:**')
            ->line('**Status:** ' . $presencaStatus)
            ->when($infoAcompanhante, function($mail) use ($infoAcompanhante) {
                return $mail->line($infoAcompanhante);
            })
            ->when($infoFilhos, function($mail) use ($infoFilhos) {
                return $mail->line($infoFilhos);
            })
            ->when($infoRestricoes, function($mail) use ($infoRestricoes) {
                return $mail->line('---')->line($infoRestricoes);
            })
            ->line('---')
            ->line('**📅 Data de Submissão:** ' . now()->format('d/m/Y H:i:s'))
            ->action('Ver Todas as Confirmações', url('/dashboard'))
            ->line('Este email foi gerado automaticamente pelo site do casamento.');
    }

    /**
     * Array representation da notificação
     */
    public function toArray($notifiable)
    {
        return [
            'confirmacao_id' => $this->confirmacao['id'] ?? null,
            'nome' => $this->confirmacao['nome'],
            'presenca' => $this->confirmacao['presenca'],
        ];
    }
}