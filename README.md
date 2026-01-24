# Sudolift - Sistema de Gestão de Academia

Este projeto é o trabalho final da disciplina de **Programação Web** do IFMG. Trata-se de um sistema web completo para gestão de atividades de uma academia, implementando o conceito de CRUD e controle de acesso baseado em perfis.

## 🎯 Objetivo e Funcionalidades

O sistema foi desenvolvido cumprindo todos os requisitos propostos para o Seminário Final:

### 1. Sistema de Autenticação e Perfis
Login seguro com suporte a diferentes níveis de acesso (Perfis), permitindo funcionalidades específicas para cada tipo de usuário:
- **Admin**: Acesso total, incluindo gestão de usuários.
- **Instrutor**: Gestão de treinos e exercícios.
- **Escrivão**: Responsável por manter as citações motivacionais.
- **Atleta/Aluno**: Acesso aos seus treinos e perfil.

### 2. Cadastros (CRUDs)
O sistema implementa mais de 3 tipos de cadastros distintos:
- **Usuários**: Cadastro completo com foto de perfil e níveis de acesso.
- **Exercícios**: Banco de dados de exercícios com imagens/vídeos demonstrativos, grupo muscular alvo e equipamento necessário.
- **Treinos e Rotinas**: Criação e gestão de rotinas de treino personalizadas para os alunos, com controle de séries, repetições e cargas.
- **Citações**: Sistema de frases motivacionais (CRUD extra gerido pelo perfil 'Escrivão').

### 3. Menu Dinâmico
A interface (Dashboard e Sidebar) adapta-se automaticamente ao usuário logado, exibindo apenas as opções permitidas para o seu perfil.
- *Exemplo*: Apenas administradores veem o "Painel Admin"; apenas escrivães veem "Editar Citações".

### 4. Gestão de Perfil e Segurança
- **Alteração de Senha**: Funcionalidade nativa na área "Meu Perfil".
- **Foto de Perfil**: Upload e atualização de imagem de avatar.
- **Segurança**: Senhas criptografadas no banco de dados.

---

## 🛠️ Tecnologias Utilizadas

- **Back-end**: PHP (Estruturado/OO)
- **Banco de Dados**: MySQL / MariaDB
- **Front-end**: HTML5, CSS3 (Estilização própria, sem frameworks pesados de CSS), JavaScript (Vanilla)
- **Servidor Web**: Apache (via XAMPP/WAMP ou servidor embutido do PHP)

## 🚀 Como Executar o Projeto

1. **Clone o repositório** ou baixe os arquivos.
2. **Banco de Dados**:
   - Crie um banco de dados no seu SGBD local (ex: MySQL Workbench, phpMyAdmin).
   - Importe o arquivo `sudolift.sql` localizado na raiz do projeto.
3. **Configuração**:
   - Abra o arquivo `controller/clsConexao.php`.
   - Ajuste as variáveis `$host`, `$usuario`, `$senha`, e `$banco` conforme o seu ambiente local (ex: localhost, root, vazio, sudolift).
4. **Execução**:
   - Coloque a pasta do projeto no diretório do seu servidor web (ex: `htdocs` do XAMPP).
   - Ou utilize o servidor embutido do PHP na raiz do projeto:
     ```bash
     php -S localhost:8000
     ```
   - Acesse no navegador: `http://localhost:8000/view/login.php` (ou o caminho correspondente).

## 📂 Estrutura de Arquivos

- `/api`: Scripts auxiliares e endpoints.
- `/assets`: Imagens, CSS (`style.css`, `perfil.css`) e Scripts JS.
- `/controller`: Lógica de negócio e controle (PHP).
- `/model`: Classes de modelo e acesso a dados.
- `/view`: Telas do sistema (Login, Dashboard, Cadastros).

## 👥 Autores

Trabalho desenvolvido para a disciplina de Programação Web.

- **Nome do Aluno 1** (Emanuel Rocha)
- **[Nome do Aluno 2]**
- **[Nome do Aluno 3]**

---

*Projeto desenvolvido estritamente para fins acadêmicos, proibido o reaproveitamento de código de terceiros conforme regras do trabalho.*
