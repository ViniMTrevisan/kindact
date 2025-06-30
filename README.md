# KindAct - Conectando Boas Ações

* **LinkedIn** - [LinkedIn](https://www.linkedin.com/feed/update/urn:li:activity:7344990253033664512/)

## Demonstração em Vídeo - CLIQUE AQUI!

[![Assista à demonstração do KindAct em vídeo](/public/assets/Captura%20de%20Tela%202025-06-29%20às%2004.22.40.png)](https://youtu.be/rszk96KI3N4?si=AqUwRi7E2HOJCGUq)

*Clique na imagem acima para ver uma demonstração completa da plataforma em ação!*

O KindAct é uma plataforma web full-stack, desenvolvida como um projeto acadêmico, com a missão de criar uma ponte entre voluntários que desejam ajudar e ONGs que precisam de ajuda. A aplicação facilita a publicação de oportunidades, a busca por vagas e a gestão de candidaturas de forma simples e segura.

**Projeto desenvolvido para a disciplina de Desenvolvimento Web do 2º semestre do curso de Engenharia de Software na Pontifícia Universidade Católica do Paraná (PUC-PR).**

---

## Índice

* [Funcionalidades Principais](#funcionalidades-principais)
* [Stack de Tecnologias](#stack-de-tecnologias)
* [Arquitetura e Segurança](#arquitetura-e-segurança)
* [Como Rodar Localmente](#como-rodar-localmente)
* [Autores](#autores)
* [Licença](#licença)

---

## Funcionalidades Principais

A plataforma foi construída com três níveis de acesso distintos, cada um com suas próprias funcionalidades:

### 👨‍💻 **Para Voluntários**
* Cadastro e Login seguros.
* Recuperação de senha via token por e-mail (simulado).
* Dashboard com listagem de oportunidades de todas as ONGs aprovadas.
* Sistema de filtro de oportunidades por área de atuação.
* Visualização de detalhes de ONGs e de vagas específicas.
* Sistema de candidatura para as vagas.
* Página "Minhas Candidaturas" para acompanhar o status (Pendente, Contactado).

### 🏢 **Para ONGs**
* Cadastro seguro, que requer aprovação de um administrador para ser ativado.
* Login e Recuperação de senha.
* Dashboard para gerenciar suas próprias oportunidades.
* CRUD completo para oportunidades: criar, ler, **editar** e **excluir** vagas.
* Visualização de todos os voluntários que se candidataram para cada vaga.
* Funcionalidade para marcar um candidato como "Contactado".

### 👑 **Para Administradores**
* Área de login exclusiva e segura.
* Dashboard de administração.
* Sistema de aprovação de novas ONGs, liberando-as para usar a plataforma.
* Gerenciamento de usuários, com a capacidade de remover ONGs e Voluntários do sistema.
* (Opcional) Capacidade de criar novos administradores a partir do painel.

---

## Stack de Tecnologias

Este projeto foi construído do zero utilizando tecnologias fundamentais do desenvolvimento web, sem o uso de frameworks, para focar no aprendizado dos conceitos essenciais.

* **Backend:** **PHP 8**
* **Banco de Dados:** **MySQL**
* **Frontend:** **HTML5**, **CSS3** (com Flexbox para layouts responsivos), e **JavaScript (ES6)** puro.
* **Servidor de Desenvolvimento:** **Apache** (através do XAMPP)

---

## Arquitetura e Segurança

A estrutura do projeto foi planejada para ser segura e organizada, seguindo boas práticas de mercado.

* **Padrão Front Controller:** Todas as requisições são direcionadas para um único ponto de entrada (`/public/index.php`), que atua como um roteador, garantindo um fluxo de controle centralizado.
* **Estrutura de Diretórios Segura:** O código-fonte (`/src`), incluindo a lógica de negócio, arquivos de conexão e views, está localizado fora do diretório público (`/public`), impedindo o acesso direto e protegendo o sistema.
* **Prevenção de SQL Injection:** Todas as consultas ao banco de dados são executadas utilizando **Prepared Statements** com `bind_param`.
* **Prevenção de Cross-Site Scripting (XSS):** Todas as saídas de dados para o HTML são tratadas com a função `htmlspecialchars` para evitar a execução de scripts maliciosos.
* **Prevenção de Cross-Site Request Forgery (CSRF):** Todos os formulários que executam ações de mudança de estado (criar, editar, deletar) são protegidos com tokens CSRF únicos por sessão.
* **Gerenciamento Seguro de Sessões:** As sessões são iniciadas de forma segura, e o ID de sessão é regenerado no momento do login para mitigar ataques de fixação de sessão.

---

## Como Rodar Localmente

Siga os passos abaixo para executar o projeto em sua máquina.

**Pré-requisitos:**
* Um ambiente de servidor local como [XAMPP](https://www.apachefriends.org/index.html) ou WAMP (com Apache, MySQL, PHP).
* Um cliente de banco de dados como phpMyAdmin (incluso no XAMPP).

**1. Clone o Repositório**
```bash
git clone [https://github.com/ViniMTrevisan/kindact.git](https://github.com/ViniMTrevisan/kindact.git)
cd kindact
```

**2. Configure o Banco de Dados**
* Abra o phpMyAdmin e crie um novo banco de dados chamado `kindact`.
* Selecione o banco `kindact` e vá para a aba "SQL".
* Copie e cole **todo** o código SQL abaixo e execute-o para criar todas as tabelas necessárias:

```sql
-- Estrutura para `tb_admin`
CREATE TABLE `tb_admin` ( `admin_id` INT AUTO_INCREMENT PRIMARY KEY, `admin_email` VARCHAR(255) NOT NULL UNIQUE, `admin_senha` VARCHAR(255) NOT NULL );

-- Estrutura para `tb_ong`
CREATE TABLE `tb_ong` ( `ong_id` INT AUTO_INCREMENT PRIMARY_KEY, `ong_nome` VARCHAR(255) NOT NULL, `ong_cnpj` VARCHAR(14) NOT NULL UNIQUE, `ong_telefone` VARCHAR(15), `ong_email` VARCHAR(255) NOT NULL UNIQUE, `ong_cep` VARCHAR(8), `ong_endereco` VARCHAR(255), `ong_area_atuacao` VARCHAR(255), `ong_senha` VARCHAR(255) NOT NULL, `aprovado` TINYINT(1) DEFAULT 0, `ong_logo_url` VARCHAR(255) NULL, `ong_redes_sociais` VARCHAR(255) NULL, `ong_descricao` TEXT NULL );

-- Estrutura para `tb_voluntario`
CREATE TABLE `tb_voluntario` ( `voluntario_id` INT AUTO_INCREMENT PRIMARY_KEY, `voluntario_nome` VARCHAR(255) NOT NULL, `voluntario_telefone` VARCHAR(15), `voluntario_email` VARCHAR(255) NOT NULL UNIQUE, `voluntario_cep` VARCHAR(8), `voluntario_endereco` VARCHAR(255), `voluntario_senha` VARCHAR(255) NOT NULL, `voluntario_habilidades` VARCHAR(255) NULL, `voluntario_bio` TEXT NULL );

-- Estrutura para `tb_evento` (Oportunidades)
CREATE TABLE `tb_evento` ( `evento_id` INT AUTO_INCREMENT PRIMARY_KEY, `evento_titulo` VARCHAR(255) NOT NULL, `evento_descricao` TEXT NOT NULL, `evento_data_inicio` DATE NOT NULL, `fk_ong_id` INT, FOREIGN KEY (`fk_ong_id`) REFERENCES `tb_ong`(`ong_id`) ON DELETE CASCADE );

-- Estrutura para `tb_candidatura`
CREATE TABLE `tb_candidatura` ( `candidatura_id` INT AUTO_INCREMENT PRIMARY_KEY, `fk_voluntario_id` INT NOT NULL, `fk_ong_id` INT NOT NULL, `fk_evento_id` INT NOT NULL, `status` VARCHAR(50) DEFAULT 'pendente', FOREIGN KEY (`fk_voluntario_id`) REFERENCES `tb_voluntario`(`voluntario_id`) ON DELETE CASCADE, FOREIGN KEY (`fk_ong_id`) REFERENCES `tb_ong`(`ong_id`) ON DELETE CASCADE, FOREIGN KEY (`fk_evento_id`) REFERENCES `tb_evento`(`evento_id`) ON DELETE CASCADE );

-- Estrutura para `tb_password_resets`
CREATE TABLE `tb_password_resets` ( `id` INT AUTO_INCREMENT PRIMARY_KEY, `user_email` VARCHAR(255) NOT NULL, `token` VARCHAR(255) NOT NULL, `expires_at` DATETIME NOT NULL );
```

**3. Configure a Conexão**
* Abra o arquivo `/src/core/db_connect.php`.
* Verifique se as credenciais (`$username`, `$password`) correspondem às do seu ambiente MySQL local. O padrão do XAMPP geralmente é `root` sem senha.

**4. Configure o Servidor Apache**
* Para máxima segurança, o `DocumentRoot` do seu servidor deve apontar para a pasta `/public`.
* No XAMPP, abra `xampp/apache/conf/httpd.conf` e altere a linha `DocumentRoot` para o caminho completo da pasta `public` no seu computador.
    * Exemplo: `DocumentRoot "C:/xampp/htdocs/kindact/public"`
* Reinicie o Apache.

**5. Crie o Primeiro Administrador**
* Acesse `http://localhost/index.php?page=form_cadastro_admin` no seu navegador.
* Preencha os dados para criar sua conta de administrador principal.
* **IMPORTANTE:** Por segurança, após criar seu admin, apague o arquivo `/src/views/form_cadastro_admin.php`.

**6. Acesse o Site**
* Pronto! Acesse `http://localhost/` e o site KindAct estará funcionando.

---

## Autores

* **Vinicius Meier Trevisan** - [GitHub](https://github.com/ViniMTrevisan)
* **Guilherme Reis Carvalho** - [GitHub](https://github.com/GuiiRCarvalho) 
* **Nicolas Lobo** - [GitHub](https://github.com/nicolasalobo) 