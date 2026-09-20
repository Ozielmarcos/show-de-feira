<?php

class MercadoPagoService {

    // Insira seu ACCESS_TOKEN do Mercado Pago abaixo ou defina na variável de ambiente MERCADO_PAGO_TOKEN
    // Obtenha em: https://www.mercadopago.com.br/developers/panel/app
    private static $accessToken = "";

   public static function getToken() {
        $token = getenv('MERCADO_PAGO_TOKEN') ?: ($_ENV['MERCADO_PAGO_TOKEN'] ?? null);

        if (empty($token)) {
            $caminhoEnv = __DIR__ . '/../.env';
            if (file_exists($caminhoEnv)) {
                $linhas = file($caminhoEnv, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
                foreach ($linhas as $linha) {
                    $linha = trim($linha);
                    if (empty($linha) || strpos($linha, '#') === 0) continue;
                    if (strpos($linha, 'MERCADO_PAGO_TOKEN=') === 0) {
                        $token = trim(substr($linha, 19));
                        break;
                    }
                }
            }
        }

        if (!empty($token)) {
            return trim($token, " \t\n\r\0\x0B\"'");
        }

        return self::$accessToken;
    }

    /**
     * Cria uma preferência de pagamento no Mercado Pago Checkout Pro
     *
     * @param array $itens Carrinho de compras
     * @param array $payer Dados do cliente logado
     * @param string|int $pedidoId ID do pedido no banco de dados
     * @param string $baseUrl URL base da aplicação
     * @return array Resposta contendo id, init_point e sandbox_init_point
     */
    public static function criarPreferencia(array $itens, array $payer, $pedidoId, string $baseUrl) {
        $token = self::getToken();
        echo "Token usado: " . substr($token, 0, 4) . "...<br>"; // DEBUG
        
        $mpItems = [];
        foreach ($itens as $item) {
            $pictureUrl = !empty($item['imagem']) ? $baseUrl . 'arquivos/' . $item['imagem'] : '';
            $mpItems[] = [
                'id' => (string)$item['id'],
                'title' => (string)$item['nome'],
                'quantity' => (int)$item['quantidade'],
                'unit_price' => (float)$item['valor'],
                'currency_id' => 'BRL',
                'picture_url' => $pictureUrl
            ];
        }

        $payload = [
            'items' => $mpItems,
            'payer' => [
                'name' => $payer['nome'] ?? 'Cliente Show de Feira',
                'email' => $payer['email'] ?? 'cliente@feira.com'
            ],
            'back_urls' => [
                'success' => $baseUrl . 'carrinho/sucesso?pedido_id=' . $pedidoId,
                'failure' => $baseUrl . 'carrinho/falha?pedido_id=' . $pedidoId,
                'pending' => $baseUrl . 'carrinho/pendente?pedido_id=' . $pedidoId
            ],
            'auto_return' => 'approved',
            'external_reference' => (string)$pedidoId,
            'statement_descriptor' => 'SHOWDEFEIRA'
        ];

        // Se o token for o padrão de exemplo ou vazio, retorna modo simulação amigável para testes
        if (empty($token) || strpos($token, 'TEST-SEU-ACCESS-TOKEN') !== false) {
            return [
                'id' => 'simulado-' . time(),
                'init_point' => $baseUrl . 'carrinho/sucesso?simulado=1&pedido_id=' . $pedidoId,
                'sandbox_init_point' => $baseUrl . 'carrinho/sucesso?simulado=1&pedido_id=' . $pedidoId,
                'simulado' => true,
                'mensagem' => 'Modo Simulação: Adicione seu Access Token em config/MercadoPagoService.php para checkout em produção do Mercado Pago.'
            ];
        }

        $ch = curl_init('https://api.mercadopago.com/checkout/preferences');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $token,
            'Content-Type: application/json'
        ]);
        curl_setopt($ch, CURLOPT_TIMEOUT, 15);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode === 201 || $httpCode === 200) {
            $data = json_decode($response, true);
            if (!empty($data['init_point'])) {
                return [
                    'id' => $data['id'],
                    'init_point' => $data['init_point'],
                    'sandbox_init_point' => $data['sandbox_init_point'] ?? $data['init_point'],
                    'simulado' => false
                ];
            }
        }

        // Em caso de erro na API (ex: token inválido), fallback para simulação com aviso
        return [
            'id' => 'fallback-' . time(),
            'init_point' => $baseUrl . 'carrinho/sucesso?simulado=1&pedido_id=' . $pedidoId,
            'sandbox_init_point' => $baseUrl . 'carrinho/sucesso?simulado=1&pedido_id=' . $pedidoId,
            'simulado' => true,
            'mensagem' => 'Não foi possível conectar ao Mercado Pago com o token fornecido. Código HTTP: ' . $httpCode
        ];
    }
}
