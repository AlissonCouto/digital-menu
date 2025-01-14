# Sistema de Gestão de Pedidos

## Sobre o Projeto
O **Sistema de Gestão de Pedidos** foi desenvolvido para auxiliar restaurantes, pizzarias, hamburguerias e outros estabelecimentos alimentícios a gerenciar seus pedidos de maneira eficaz e moderna. Ele oferece funcionalidades como visualização de cardápios digitais, gerenciamento de pedidos em tempo real, controle de entregas e geração de relatórios.

## Tecnologias Utilizadas
- **Back-End:**
  - PHP 8+ e Laravel
  - MySQL
  - Laravel Echo, Pusher.js (WebSockets)
  - Laravel Vite Plugin
- **Front-End:**
  - Vite
  - TailwindCSS
  - Alpine.js
  - Chart.js
  - Axios
  - PostCSS, Autoprefixer

## Funcionalidades
- **Cardápio Digital:** Exibição de produtos de forma interativa e moderna para os clientes.
- **Rastreamento de Pedidos:** Acompanhe em tempo real os status dos pedidos.
- **Gestão de Entregas:** Controle as entregas, incluindo áreas de cobertura e informações dos entregadores.
- **Relatórios de Vendas:** Gerenciamento de dados de vendas e análise de desempenho dos produtos utilizando gráficos.
- **Notificações em Tempo Real:** Notifique os administradores e clientes sobre o status do pedido via WebSockets.

## Requisitos
- **PHP:** 8.0 ou superior.
- **Composer:** Para gerenciar dependências PHP.
- **Node.js e npm:** Para dependências do front-end.
- **MySQL:** 5.7 ou superior.
- **Extensões PHP:** OpenSSL, PDO, Mbstring, Tokenizer, XML.

## Como Rodar o Projeto

### Passo 1: Clonar o Repositório
```bash
git clone https://github.com/AlissonCouto/digital-menu.git
cd digital-menu
```

### Passo 2: Instalar Dependências PHP
```bash
composer install
```

### Passo 3: Configurar o Arquivo .env
Renomeie o arquivo `.env.example` para `.env`:

```bash
cp .env.example .env
```

### Passo 4: Gerar a Chave da Aplicação
```bash
php artisan key:generate
```

### Passo 5: Executar Migrações e Seeders
```bash
php artisan migrate --seed
```

### Passo 6: Instalar Dependências Front-End
```bash
npm install
```

### Passo 7: Compilar os Assets do Front-End
```bash
npm run dev
```

### Passo 8: Iniciar o Servidor
Execute os seguintes comandos em terminais separados:

- **Servidor PHP:**
```bash
php artisan serve
```


- **WebSocket:**
```bash
php artisan reverb:start
```

- **Gerenciador de Filas:**
```bash
php artisan queue:work
```

O sistema estará acessível em: http://localhost:8000.

## Dependências

### Dependências de Desenvolvimento
- **TailwindCSS**: Framework de CSS para estilização.
- **Alpine.js**: Biblioteca JavaScript para interatividade.
- **PostCSS**: Ferramenta de processamento de CSS.
- **Autoprefixer**: Garante a compatibilidade do CSS com vários navegadores.
- **Vite**: Ferramenta para build de front-end.

### Dependências
- **Chart.js**: Biblioteca para visualização de gráficos.

## Contribuição
Sinta-se à vontade para contribuir com o projeto. Abra issues ou envie pull requests.

## Licença
Este projeto é licenciado sob a MIT License.
