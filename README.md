# FinanceControl

O **FinanceControl** é uma aplicação web desenvolvida para gerenciar finanças pessoais e empresariais. Ele permite o controle de vendas, clientes, produtos, transações financeiras, bancos e muito mais, com uma interface intuitiva e funcionalidades avançadas.

## Funcionalidades Principais

- **Gestão de Vendas**: Controle completo de vendas, incluindo exportação de relatórios em PDF.
- **Gestão de Clientes**: Cadastro, edição e histórico de vendas de clientes.
- **Gestão de Produtos**: Cadastro de produtos com imagens, preços e categorias.
- **Livro Caixa**: Controle de receitas e despesas mensais, com gráficos e relatórios.
- **Gestão de Bancos**: Controle de cartões e transações bancárias.
- **Relatórios e Gráficos**: Visualize dados financeiros de forma clara e organizada.

## Tecnologias Utilizadas

- **Backend**: Laravel (PHP)
- **Frontend**: Blade Templates, Bootstrap
- **Banco de Dados**: MySQL
- **Outras Bibliotecas**:
  - FullCalendar.js
  - Chart.js
  - Cropper.js
  - Toastr.js

## Requisitos

- PHP >= 8.0
- Composer
- MySQL
- Node.js e npm (para gerenciamento de dependências do frontend)

## Instalação

1. Clone o repositório:
   ```bash
   git clone https://github.com/seu-usuario/FinanceControl.git
   cd FinanceControl
   ```

2. Instale as dependências do PHP:
   ```bash
   composer install
   ```

3. Instale as dependências do Node.js:
   ```bash
   npm install
   ```

4. Configure o arquivo `.env`:
   - Copie o arquivo de exemplo:
     ```bash
     cp .env.example .env
     ```
   - Configure as variáveis de ambiente, como conexão com o banco de dados.

5. Gere a chave da aplicação:
   ```bash
   php artisan key:generate
   ```

6. Execute as migrações e seeders:
   ```bash
   php artisan migrate --seed
   ```

7. Compile os assets do frontend:
   ```bash
   npm run dev
   ```

8. Inicie o servidor:
   ```bash
   php artisan serve
   ```

Acesse a aplicação em [http://localhost:8000](http://localhost:8000).

## Estrutura do Projeto

- `resources/views`: Contém os templates Blade para as páginas da aplicação.
- `app/Models`: Modelos Eloquent para interação com o banco de dados.
- `app/Http/Controllers`: Controladores responsáveis pela lógica de negócio.
- `public`: Arquivos públicos, como imagens e CSS compilado.

## Contribuição

Contribuições são bem-vindas! Siga os passos abaixo para contribuir:

1. Faça um fork do repositório.
2. Crie uma branch para sua feature:
   ```bash
   git checkout -b minha-feature
   ```
3. Faça commit das suas alterações:
   ```bash
   git commit -m "Descrição da minha feature"
   ```
4. Envie para o repositório remoto:
   ```bash
   git push origin minha-feature
   ```
5. Abra um Pull Request.

## Licença

Este projeto está licenciado sob a [MIT License](LICENSE).

## Contato

Para dúvidas ou sugestões, entre em contato pelo e-mail: **seu-email@exemplo.com**.
