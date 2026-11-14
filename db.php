<?php
$pdo = new PDO("pgsql:host=localhost;dbname=classwall", "postgres", "1008");
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
