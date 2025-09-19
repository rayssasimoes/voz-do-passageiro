<?php
// Arquivo: src/backend/models/Reclamacao.php

class Reclamacao {

    /**
     * Busca as 5 reclamações mais recentes no banco de dados.
     * @param PDO $pdo O objeto de conexão com o banco.
     * @return array A lista de reclamações.
     */
    public static function buscarRecentes($pdo) {
        $stmt = $pdo->query("SELECT linha_onibus, tipo_reclamacao, descricao, autor, DATE_FORMAT(data_hora, '%d/%m/%Y às %H:%i') as data_formatada FROM reclamacoes ORDER BY data_hora DESC LIMIT 5");
        return $stmt->fetchAll();
    }

    /**
     * Busca o ranking das 5 piores linhas de ônibus (com mais reclamações).
     * @param PDO $pdo O objeto de conexão com o banco.
     * @return array A lista das piores linhas.
     */
    public static function buscarPioresLinhas($pdo) {
        $stmt = $pdo->query("SELECT linha_onibus, COUNT(*) as total_reclamacoes FROM reclamacoes GROUP BY linha_onibus ORDER BY total_reclamacoes DESC LIMIT 5");
        return $stmt->fetchAll();
    }

    /**
     * Busca o ranking das 5 melhores linhas (com menos reclamações), excluindo as piores.
     * @param PDO $pdo O objeto de conexão com o banco.
     * @param array $piores_linhas_nomes A lista de nomes das piores linhas para excluir da busca.
     * @return array A lista das melhores linhas.
     */
    public static function buscarMelhoresLinhas($pdo, $piores_linhas_nomes) {
        if (empty($piores_linhas_nomes)) {
            $stmt = $pdo->query("SELECT linha_onibus, COUNT(*) as total_reclamacoes FROM reclamacoes GROUP BY linha_onibus HAVING total_reclamacoes > 0 ORDER BY total_reclamacoes ASC LIMIT 5");
        } else {
            $placeholders = implode(',', array_fill(0, count($piores_linhas_nomes), '?'));
            $stmt = $pdo->prepare("SELECT linha_onibus, COUNT(*) as total_reclamacoes FROM reclamacoes WHERE linha_onibus NOT IN ($placeholders) GROUP BY linha_onibus HAVING total_reclamacoes > 0 ORDER BY total_reclamacoes ASC LIMIT 5");
            $stmt->execute($piores_linhas_nomes);
        }
        return $stmt->fetchAll();
    }

    // Nota: A lógica da API de registrar reclamação será movida depois para um "Controller".
    // Por enquanto, deixamos o Model pronto para o futuro.
}