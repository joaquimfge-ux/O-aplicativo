# Sistema de Dispositivos de Armazenamento Personalizados (CustomStorage)

Sistema web desenvolvido integralmente em conformidade com o documento de especificação de requisitos de software (Casos de Uso UC01 a UC16, Requisitos Funcionais RF01 a RF27, Requisitos Não Funcionais RNF01 a RNF08, DER e Critérios de Aceite CA01 a CA15).

---

## 📁 Estrutura de Pastas

Todos os arquivos do projeto foram organizados em pastas separadas dentro de um único diretório raiz em `C:\Documentos\dispositivos_armazenamento`:

```
C:\Documentos\dispositivos_armazenamento\
├── config/                  # Configurações globais e banco de dados
│   ├── config.php           # Constantes, rotas, inicialização de sessão segura
│   └── database.php         # Conexão PDO com MySQL (Singleton, utf8mb4)
├── database/                # Scripts SQL e migração
│   ├── schema.sql           # DDL completo das tabelas (DER)
│   ├── setup.php            # Script de criação automática e população inicial
│   └── seed.sql             # Dados de teste (formatos, capacidades, dispositivos)
├── src/
│   ├── Models/              # Camada de Dados e Regras de Negócio
│   │   ├── Usuario.php      # RF01-03, RF24-27 (CRUD, RBAC, Senhas BCRYPT, Desativação)
│   │   ├── Formato.php      # DER: Formato 1:N Dispositivo (CRUD, Validação FK)
│   │   ├── Dispositivo.php  # RF04-05, RF20-23 (Modelos físicos, Preços base)
│   │   ├── Capacidade.php   # RF06-07 (Capacidades de GB/TB e preços adicionais)
│   │   ├── Pedido.php       # RF12-19 (Transação atômica, cálculo seguro, status)
│   │   └── Personalizacao.php # RF08-11 (Relacionamento 0:1, acompanhamento de customizações)
│   ├── Controllers/         # Camada de Controle
│   │   ├── AuthController.php        # Login, Cadastro, Logout, Validações
│   │   ├── ClienteController.php     # Catálogo, Detalhes, Pedidos, Meus Pedidos
│   │   ├── FuncionarioController.php # Central de Pedidos, Atualização de Status
│   │   └── AdminController.php       # Painel Geral, CRUD Dispositivos, Usuários e Configurações
│   └── Helpers/             # Utilitários
│       ├── Auth.php         # Controle de Acesso por Perfil (RBAC) e Sessão
│       └── View.php         # Renderizador de views, Tokens CSRF, Flash Messages, Formatações
├── views/                   # Camada de Apresentação (Interface)
│   ├── partials/            # Header, Navbar (RBAC dinâmico), Footer, Flash Alerts
│   ├── auth/                # login.php, cadastro.php
│   ├── cliente/             # catalogo.php, dispositivo_detalhes.php, novo_pedido.php, meus_pedidos.php, pedido_detalhes.php
│   ├── funcionario/         # pedidos.php, pedido_detalhes.php, personalizacoes.php
│   └── admin/               # dashboard.php, produtos.php, dispositivo_form.php (Jogos), usuario_form.php (Usuários), formatos.php, capacidades.php, usuarios.php, pedidos.php, personalizacoes.php
├── public/                  # Arquivos Públicos
│   ├── css/style.css        # Folha de estilo moderna, responsiva com tema refinado
│   ├── js/main.js           # Cálculo em tempo real de capacidade e personalização
│   └── images/              # Ícones e mockups gráficos
├── index.php                # Front Controller / Roteador Principal
├── router.php               # Roteador para servidor de desenvolvimento embutido do PHP
└── .htaccess                # Configuração para Apache (mod_rewrite)
```

---

## 👥 Credenciais de Acesso (Demonstração)

O banco de dados já vem configurado e populado com usuários de teste para cada perfil de acesso:

| Perfil | E-mail | Senha | Funcionalidades |
| :--- | :--- | :--- | :--- |
| **Administrador** | `admin@sistema.com` | `admin123` | Acesso total: CRUD de dispositivos, formatos, capacidades, gerenciamento de equipe/usuários (desativação de contas), todos os pedidos e personalizações |
| **Funcionário** | `funcionario@sistema.com` | `func123` | Consulta e atualização de status dos pedidos de clientes, acompanhamento de personalizações |
| **Cliente** | `cliente@sistema.com` | `cliente123` | Catálogo, escolha de modelo pronto ou personalizado, escolha de capacidade, realização e consulta dos próprios pedidos |

> *Dica: Na tela de login há botões de preenchimento rápido com 1 clique para agilizar seus testes.*

---

## 🚀 Como Executar o Sistema

### Opção 1: Servidor Embutido do PHP (Recomendado / Mais Rápido)
Abra o terminal (PowerShell ou CMD) na pasta do projeto e execute:
```bash
cd C:\Documentos\dispositivos_armazenamento
php -S localhost:8000 router.php
```
Em seguida, abra no navegador:
**[http://localhost:8000](http://localhost:8000)**

---

### Opção 2: Servidor Apache / Laragon / XAMPP
O sistema foi projetado para funcionar no Apache com `.htaccess` ativo. Se desejar usar o Laragon ou XAMPP:
1. Aponte o DocumentRoot ou crie um VirtualHost/Alias apontando para `C:\Documentos\dispositivos_armazenamento`
2. Certifique-se de que o MySQL esteja ativo na porta `3306`.

---

## ⚙️ Banco de Dados (MySQL)
Caso queira reinstalar ou resetar o banco de dados e os dados de teste a qualquer momento, execute:
```bash
php C:\Documentos\dispositivos_armazenamento\database\setup.php
```
- **Banco criado:** `dispositivos_db`
- **Tabelas criadas conforme o Modelo de Dados (DER):**
  - `usuario`
  - `formato`
  - `dispositivo`
  - `capacidade`
  - `pedido`
  - `personalizacao`

---

## ✅ Cobertura de Requisitos da Especificação

- **RF01 e CA01:** Cadastro de usuário com validação de campos.
- **RF02 e CA02:** Login seguro com verificação de credenciais e integridade.
- **RF03, RNF03, RNF04 e CA03:** Controle estrito de acesso (RBAC) com bloqueio a não autenticados e rotas restritas por perfil.
- **RF04, RF05, CA04 e CA05:** Consulta e seleção de modelos e formatos físicos.
- **RF06, RF07 e CA06:** Consulta e escolha de capacidade de armazenamento com cálculo dinâmico de adicionais.
- **RF08, RF09 e CA07:** Solicitação de personalização com características (gravação a laser, conteúdo pré-carregado: jogos, fotos, vídeos, acabamento).
- **RF10 e CA08:** Consulta e acompanhamento de personalizações pelos funcionários.
- **RF11 e CA15:** Gerenciamento administrativo de solicitações de personalização.
- **RF12, RF13 e CA09:** Realização e registro de pedidos (prontos ou personalizados) com transação segura no banco de dados.
- **RF14 e CA10:** Consulta dos próprios pedidos com isolamento de privacidade por cliente (RNF08).
- **RF15, RF16 e RF17:** Consulta de pedidos, detalhes e atualização de status por funcionários (CA11).
- **RF18 e RF19:** Consulta de todos os pedidos e gestão pelo administrador (CA14).
- **RF20 a RF23:** CRUD completo de modelos de dispositivos pelo administrador (CA12).
- **RF24 a RF27:** Gerenciamento de usuários, cadastro de funcionários, edição e desativação de contas (CA13).
- **RNF01:** Hashing seguro de senhas com BCRYPT (`password_hash`).
- **RNF02:** Validação total no lado do servidor de todos os formulários e valores monetários.
- **RNF05:** Integridade referencial com chaves primárias (PK), chaves estrangeiras (FK) e transações atômicas no MySQL.
