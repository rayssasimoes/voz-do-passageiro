<?php

// Adiciona a declaração do namespace para o arquivo
namespace Backend\Models;

use PDO;

class Reclamacao {

    /**
     * Cria uma nova reclamação no banco de dados.
     *
     * @param PDO $pdo
     * @param array $dados
     * @return boolean
     */
    public static function criar(PDO $pdo, array $dados): bool
    {
        $sql = "INSERT INTO reclamacao (linha_onibus, tipo_reclamacao, descricao, usuario_id, autor) VALUES (?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        
        // Define o autor como nulo se o usuário não quiser mostrar o nome
        $autor = $dados['mostrar_nome'] ? $dados['usuario_nome'] : 'Anônimo';

        return $stmt->execute([
            $dados['linha_onibus'],
            $dados['tipo_reclamacao'],
            $dados['descricao'],
            $dados['usuario_id'],
            $autor
        ]);
    }

    /**
     * Busca todas as reclamações de um usuário específico.
     *
     * @param PDO $pdo
     * @param integer $id_usuario
     * @return array
     */
    public static function buscarPorUsuario(PDO $pdo, int $id_usuario): array
    {
        $sql = "SELECT reclamacao_id, linha_onibus, tipo_reclamacao, descricao, DATE_FORMAT(data_hora, '%d/%m/%Y') AS data_formatada FROM reclamacao WHERE usuario_id = ? ORDER BY data_hora DESC";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id_usuario]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Deleta uma reclamação, verificando se pertence ao usuário.
     *
     * @param PDO $pdo
     * @param integer $id_reclamacao
     * @param integer $id_usuario
     * @return boolean
     */
    public static function excluir(PDO $pdo, int $id_reclamacao, int $id_usuario): bool
    {
        $sql = "DELETE FROM reclamacao WHERE reclamacao_id = ? AND usuario_id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id_reclamacao, $id_usuario]);
        return $stmt->rowCount() > 0;
    }

    /**
     * Obtém as reclamações mais recentes.
     * @param PDO $pdo Conexão com o banco de dados.
     * @return array
     */
    public static function buscarRecentes(PDO $pdo): array {
        $sql = "SELECT linha_onibus, tipo_reclamacao, descricao, autor, 
                       DATE_FORMAT(data_hora, '%d/%m/%Y às %H:%i') AS data_formatada
                FROM reclamacao
                ORDER BY data_hora DESC LIMIT 10";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Obtém as linhas com o maior número de reclamações.
     * @param PDO $pdo Conexão com o banco de dados.
     * @return array
     */
    public static function buscarPioresLinhas(PDO $pdo): array {
        $sql = "SELECT linha_onibus, COUNT(*) AS total_reclamacoes
                FROM reclamacao
                GROUP BY linha_onibus
                ORDER BY total_reclamacoes DESC LIMIT 5";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Obtém as linhas com o menor número de reclamações, excluindo as piores.
     * @param PDO $pdo Conexão com o banco de dados.
     * @param array $piores_linhas_nomes Nomes das piores linhas a serem excluídas.
     * @return array
     */
    public static function buscarMelhoresLinhas(PDO $pdo, array $piores_linhas_nomes): array { // Nome do método ajustado, adicionado $piores_linhas_nomes
        if (empty($piores_linhas_nomes)) {
            $sql = "SELECT linha_onibus, COUNT(*) AS total_reclamacoes
                    FROM reclamacao
                    GROUP BY linha_onibus
                    HAVING total_reclamacoes > 0 -- Exclui linhas sem reclamações se necessário
                    ORDER BY total_reclamacoes ASC LIMIT 5";
            $stmt = $pdo->prepare($sql);
            $stmt->execute();
        } else {
            // Cria placeholders para a cláusula NOT IN
            $placeholders = implode(',', array_fill(0, count($piores_linhas_nomes), '?'));
            $sql = "SELECT linha_onibus, COUNT(*) AS total_reclamacoes
                    FROM reclamacao
                    WHERE linha_onibus NOT IN ($placeholders)
                    GROUP BY linha_onibus
                    HAVING total_reclamacoes > 0
                    ORDER BY total_reclamacoes ASC LIMIT 5";
            $stmt = $pdo->prepare($sql);
            $stmt->execute($piores_linhas_nomes); // Passa os nomes como parâmetros
        }
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}