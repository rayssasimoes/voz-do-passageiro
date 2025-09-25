<?php

namespace Backend\Models;

use PDO;
use Exception;

class Usuario {
    public static function buscarPorEmail(PDO $pdo, $email) {
        $stmt = $pdo->prepare("SELECT * FROM usuario WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function criar(PDO $pdo, $nome, $email, $senhaHash) {
        $stmt = $pdo->prepare("INSERT INTO usuario (nome, email, senha) VALUES (:nome, :email, :senha)");
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':senha', $senhaHash);
        return $stmt->execute();
    }

    public static function buscarPorId(PDO $pdo, $id_usuario) {
        $stmt = $pdo->prepare("SELECT id_usuario, nome, email FROM usuario WHERE id_usuario = :id_usuario");
        $stmt->bindParam(':id_usuario', $id_usuario);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    
    // Este método não foi usado diretamente aqui, mas é bom mantê-lo
    public static function verificarSenha($senhaDigitada, $senhaHashArmazenada) {
        return password_verify($senhaDigitada, $senhaHashArmazenada);
    }
}