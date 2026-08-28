# Stack Tecnológica e Guia de Implementação — Flor de Marula

Este documento descreve tudo o que foi usado para construir este site
(tecnologias, ferramentas, arquitetura, convenções e fluxo de trabalho),
para servir de referência ao replicar a mesma abordagem noutro projeto.

---

## 1. Resumo da stack

| Camada | Tecnologia |
|---|---|
| Linguagem/backend | PHP 8.4 |
| Framework | Laravel 13 |
| Base de dados | MySQL |
| Frontend | Blade (server-rendered), sem SPA/framework JS |
| CSS | CSS puro com custom properties (variáveis), + Bootstrap 5 (CSS pronto, sem Sass) |
| JavaScript | Vanilla JS (sem jQuery/React/Vue), módulos ES separados por página |
| Build de assets | Vite 8 + `laravel-vite-plugin` |
| Autenticação | 2 guards Laravel (`web` para clientes, `admin` para painel administrativo) — sem pacotes de terceiros (Breeze/Jetstream/Filament) |
| Armazenamento de ficheiros | `Storage::disk('public')` (uploads) + `public/images` (assets estáticos versionados no git) |
| E-mail | SMTP via `MAIL_MAILER` (Hostinger Email ou Resend/Mailgun) — só usado para notificações de encomenda |
| Pagamentos | **Sem gateway externo** — métodos de pagamento são registos numa tabela `payment_methods`, geridos no admin (ex.: pagamento na entrega, transferência) |
| APIs externas | Nenhuma API paga/com chave. Único serviço externo é um `<iframe>` do Google Maps (sem API key) |
| Hosting | Hostinger (hosting partilhado, sem Node.js no servidor) |
| Deploy | Git + SSH + script `deploy.sh` (sem CI/CD, sem Docker) |
| Controlo de versões | Git + GitHub, deploy key só de leitura no servidor |

**Porquê esta combinação**: é a stack mais barata/simples possível para um
e-commerce pequeno em hosting partilhado — sem custos de infraestrutura
além do próprio hosting, sem dependências de serviços pagos, e com um
único binário PHP a servir tudo (nada de containers, filas, cache externo
como Redis, etc., embora o Laravel já venha preparado para escalar para
isso se um dia for preciso).

---

## 2. Backend — Laravel 13 / PHP 8.4

- Projeto Laravel "puro" (`laravel/laravel` como esqueleto), sem pacotes de
  admin/CMS prontos. O painel administrativo foi construído à mão
  (controllers + Blade + CSS próprios) — ver secção 6.
- Sem API REST/JSON separada — os controllers devolvem `view()` (Blade) ou
  `redirect()`. Não há SPA nem consumo de API pelo frontend.
- Sem filas (`ShouldQueue`) nem broadcasting em uso — `QUEUE_CONNECTION`
  fica no valor por omissão (`database`) só por precaução.
- **Nota de compatibilidade**: `composer.json` pede `php: ^8.3`, mas o
  `composer.lock` resolvido (Symfony ^8.1 como dependência do Laravel)
  **exige PHP ≥ 8.4.1** em tempo de execução. Confirmar sempre a versão
  real do PHP no servidor antes de assumir que `^8.3` no `composer.json`
  chega.

### Estrutura de controllers

- Controllers "públicos" (loja): `HomeController`, `ShopController`,
  `ProductController`, `CartController`, `CheckoutController`,
  `OrderController`, `AccountController`, `ReviewController`,
  `FavoriteController`, `AjudaController`, `HistoriaController`,
  `QuizController`, `NewsletterController`, `SupportMessageController`,
  `SitemapController`.
- Controllers do admin, isolados em `App\Http\Controllers\Admin\*`
  (namespace + prefixo de rota `/admin` — ver secção 6).
- Lógica de negócio mais complexa isolada em `App\Services` (ex.:
  `OrderService` para criar encomendas a partir do carrinho) em vez de
  inchar os controllers.

---

## 3. Frontend — Blade + Bootstrap + CSS próprio

Sem framework JS. Cada página é uma view Blade normal, renderizada no
servidor. Interatividade (dropdowns, carrosséis, formulários dinâmicos no
admin) é feita com JavaScript vanilla, pouco código, sem build de
componentes.

### Ficheiros JS (`resources/js/`)

| Ficheiro | Para quê |
|---|---|
| `app.js` | Site público: pesquisa expansível, dots de carrossel |
| `product.js` | Página de detalhes do produto (galeria, seletor de ofertas, etc.) |
| `admin.js` | Painel admin: sidebar mobile, forms dinâmicos (ver abaixo), confirmações de eliminação |

**Padrão "form dinâmico" no admin** (`submitDynamicForm` em `admin.js`):
para ações que precisam de ficheiro + campos vindos de inputs soltos (não
dentro de um `<form>` visível), cria-se um `<form>` escondido em JS, injeta
o CSRF token, os campos e o ficheiro, e submete — evita reescrever a
página inteira num `<form>` só para uma ação pontual (ex.: "atualizar
imagem desta oferta").

### CSS (`resources/css/`)

- **Sem framework CSS-in-JS, sem Tailwind.** CSS puro, um ficheiro por
  secção/página, todos importados a partir de `app.css` via `@import`:
  `variables.css`, `typography.css`, `components.css`, e depois um
  ficheiro por página (`home.css`, `shop.css`, `product.css`, etc.), com
  `responsive.css` **sempre por último** (contém só overrides mobile, ver
  abaixo).
- **`variables.css`** define os tokens de design em `:root` (cores,
  radius, sombras, gutters) como `--fm-*`. Tudo o resto consome estas
  variáveis em vez de valores soltos — trocar a paleta é editar um
  ficheiro só.
- **Convenção de nomes**: todas as classes próprias do projeto usam o
  prefixo `fm-` (`fm-btn`, `fm-product-card`, `fm-compare-card__list`),
  para nunca colidir com classes do Bootstrap. Segue-se BEM-like
  (`bloco__elemento--modificador`, ex.: `fm-benefits-card__africa`).
- **Mobile-first invertido**: a maior parte do CSS é escrita "desktop
  primeiro" (regra base) com overrides em `@media (max-width: 991.98px)`
  dentro do próprio ficheiro da secção, **ou** centralizados em
  `responsive.css` quando a diferença é grande (o mobile não é só o
  desktop "espremido" — muda tipografia, empilhamento e até fundo por
  secção, conforme o design original em Figma).
- **Bootstrap** é usado só para o grid (`row`/`col-*`) e utilitários de
  espaçamento (`py-4`, `d-flex`, `gap-3`, etc.) — o CSS de componentes do
  Bootstrap (botões, cards) é sobreposto pelas classes `fm-*` próprias.
  Ficheiro consumido pronto (`bootstrap/dist/css/bootstrap.min.css`), sem
  customizar via Sass (o pacote `sass` está no `package.json` mas não é
  usado nos ficheiros do projeto).

---

## 4. Build de assets — Vite

- `vite.config.js` regista **múltiplos entry points** (não um bundle
  único): `app.css`, `admin.css`, `app.js`, `product.js`, `admin.js`.
  Cada layout Blade (`layouts.app`, `layouts.admin`... ver o admin) só
  carrega os assets de que precisa via `@vite([...])`.
- Comando de build: `npm run build` → gera `public/build/` com hashes no
  nome dos ficheiros e um `manifest.json` que o Laravel usa para
  resolver os caminhos certos em produção.
- **O servidor de produção não tem Node.js instalado** (comum em hosting
  partilhado). Por isso o build corre sempre **localmente**, e só a pasta
  `public/build/` compilada é enviada por `scp` — nunca se corre `npm` no
  servidor. Isto é uma decisão de arquitetura chave a repetir: qualquer
  alteração a `.css`/`.js` obriga a `npm run build` local + upload manual
  de `public/build/`, **antes** de correr o `deploy.sh` (que só trata do
  código PHP/Blade via git).

---

## 5. Base de dados

- MySQL, migrations Laravel normais (`database/migrations/`).
- **Seeders usados só para o catálogo inicial** (`ProductSeeder`,
  `CategorySeeder`, `AdminSeeder`). Lição aprendida neste projeto: os
  seeders de produtos devem usar `firstOrCreate` (não `updateOrCreate`)
  depois do primeiro deploy — caso contrário, re-executar um seeder (por
  exemplo para adicionar um campo novo a produtos já existentes) **apaga
  silenciosamente edições feitas no painel admin** (imagem, preço,
  stock), porque `updateOrCreate` sobrescreve todos os campos do array a
  cada execução.
- Principais entidades: `Product` (+ `ProductImage`, `ProductOffer`,
  `Ingredient`, `Faq`), `Category`, `Order` (+ `OrderItem`,
  `OrderStatusHistory`), `Cart`/`CartItem`, `Coupon`, `PaymentMethod`,
  `ShippingMethod`, `Review`, `Testimonial`, `Address`, `Favorite`,
  `StockMovement`, `SiteSetting` (configurações globais editáveis no
  admin, ex.: telefone/email/morada), `SupportMessage`,
  `NewsletterSubscriber`, `Admin` (utilizadores do painel, separado de
  `User`).

---

## 6. Autenticação — dois guards, sem pacotes de terceiros

`config/auth.php` define dois guards independentes, cada um com o seu
próprio "provider" (modelo Eloquent):

```php
'guards' => [
    'web'   => ['driver' => 'session', 'provider' => 'users'],  // clientes
    'admin' => ['driver' => 'session', 'provider' => 'admins'], // painel admin
],
```

- `App\Models\User` = clientes da loja (conta, encomendas, favoritos).
- `App\Models\Admin` = utilizadores do painel administrativo — tabela e
  modelo totalmente separados dos clientes (nunca um cliente consegue
  aceder ao admin, mesmo com o mesmo email).
- Login do admin feito à mão (`Admin\AuthController`), sem Breeze/Fortify.
  Middleware próprio a proteger `/admin/*` (redireciona para o login do
  admin, não o do site).

---

## 7. Painel administrativo — construído do zero

Sem Filament, Nova, Voyager, etc. — todas as telas do admin são
controllers + views Blade próprias em `resources/views/admin/`, com o seu
próprio layout (`layouts.admin` ou equivalente) e CSS dedicado
(`admin.css`, entry point Vite separado do site público).

Estrutura típica de um recurso do admin (ex.: Produtos):
- `Admin\ProductController` — CRUD completo (`index`, `create`, `store`,
  `edit`, `update`, `destroy`), mais ações extra específicas do domínio
  (`duplicate`, `toggleActive`, `destroyImage`).
- Sub-recursos relacionados ganham o seu próprio controller pequeno em
  vez de inchar o principal — ex.: `Admin\ProductOfferController` só
  trata das "Ofertas Exclusivas" de um produto (`store`, `update`,
  `destroy`, e um `move` para reordenar com um botão ↑/↓ que troca
  `sort_order` entre dois registos vizinhos).
- Formulário reutilizado entre `create` e `edit` num partial só
  (`_form.blade.php`), recebendo `$product` opcional.
- Rotas do admin agrupadas com prefixo `/admin` e nomes `admin.*` num
  ficheiro de rotas próprio (`routes/admin.php`), fora de `web.php`.

---

## 8. Armazenamento de imagens

Duas origens distintas, por design:

1. **Assets estáticos do design** (ícones, banners, imagens de produto
   originais do catálogo inicial) — ficam em `public/images/...`,
   versionados no git, referenciados via `asset('images/...')`. Nunca
   mudam de nome.
2. **Uploads feitos pelo admin** (nova imagem de produto, oferta,
   avaliação de cliente) — vão para `Storage::disk('public')` com
   `->store('pasta', 'public')`, que gera um **nome de ficheiro
   aleatório único** a cada upload. Isto evita problemas de cache: como o
   caminho muda sempre, não há risco de o browser/CDN mostrar uma imagem
   antiga em cache depois de a trocar.

No servidor, `storage/app/public` é exposto via link simbólico dentro de
`public_html/storage` (ver secção 9) — **nunca usar
`php artisan storage:link`** neste tipo de hosting partilhado, porque ele
cria o link dentro de `public/storage`, que não fica acessível a partir de
`public_html`.

---

## 9. Hosting e Deploy — Hostinger (hosting partilhado)

Documentado em detalhe em `DEPLOY.md` (na raiz do projeto). Resumo dos
pontos que valem a pena repetir noutro projeto:

- **O document root de `public_html` não pode ser alterado** em planos
  partilhados — fica sempre fixo, não se pode apontar para `public/` do
  Laravel. Solução: o projeto Laravel inteiro vive **fora** de
  `public_html` (ex.: `~/nome-do-projeto`), e dentro de `public_html`
  ficam só `index.php` + `.htaccess` (copiados do `public/` do Laravel,
  com os caminhos ajustados) e **links simbólicos** para as pastas
  estáticas (`build`, `images`, `videos`, `storage`).
- **Git + deploy key** — o servidor faz `git pull` a partir de uma chave
  SSH dedicada, só de leitura, registada como *Deploy key* no repositório
  GitHub (não a chave pessoal da conta) — assim, se o servidor for
  comprometido, só esse repositório fica exposto.
- **`deploy.sh` no servidor** faz tudo num comando: `git pull` → composer
  install (sem dev) → `migrate --force` → limpar e recriar caches de
  config/rotas/views. Não mexe em assets JS/CSS (ver secção 4).
- **PHP explícito por caminho absoluto** (`/opt/alt/php84/usr/bin/php`)
  em vez de confiar no `php` do `PATH` do shell SSH — em hosting
  partilhado o `php` "default" do terminal costuma ser uma versão mais
  antiga do que a configurada para o domínio no painel (hPanel).
- **Sem CI/CD** — deploy é manual, disparado por SSH depois de dar
  `git push` a partir da máquina local.

---

## 10. Fluxo de trabalho de desenvolvimento (usado neste projeto com IA)

Esta parte não é código, mas foi uma parte central de "como" este projeto
foi construído e vale a pena replicar:

1. **Implementar localmente** — editar Blade/CSS/PHP.
2. **Validar antes de subir**:
   - `php -l ficheiro.php` (lint de sintaxe) em cada ficheiro PHP/Blade
     alterado — importante quando não há PHP local instalado na máquina
     de desenvolvimento com a versão certa: lintar via SSH contra o PHP
     real do servidor (`cat ficheiro | ssh servidor "cat > /tmp/x.php &&
     php -l /tmp/x.php"`), evitando instalar nada localmente.
   - `npm run build` — falha alto e visível se houver erro de sintaxe
     CSS/JS.
3. **Commit com mensagem descritiva** (em português, no idioma do
   projeto) explicando o "porquê", não só o "quê".
4. **Deploy**: `git push` → SSH → `deploy.sh` no servidor → `scp` da
   pasta `public/build/` compilada localmente.
5. **Verificar em produção por conteúdo, não só por status HTTP** — depois
   de cada deploy, ir buscar o CSS/HTML publicado (`curl` + `grep`/`md5`)
   e confirmar que a alteração especifica está mesmo lá, em vez de
   assumir que "deploy correu sem erro" = "alteração está visível".
   Ficheiros com hash no nome (`app-XXXXXXXX.css`) tornam isto trivial:
   basta confirmar que o `manifest.json` aponta para o hash novo e que
   esse ficheiro contém a regra esperada.
6. **Nunca assumir cache do browser/CDN** — se uma alteração "não
   aparece", confirmar primeiro via `curl` direto ao ficheiro (que
   ignora cache do browser) antes de concluir que o deploy falhou.

---

## 11. Como replicar do zero — checklist

1. `laravel new nome-do-projeto` (ou clonar o esqueleto oficial).
2. `npm install` + adicionar `bootstrap` e `@popperjs/core`; configurar
   `vite.config.js` com **múltiplos entry points** (um CSS/JS por área
   da app — público vs. admin, por exemplo) em vez de um bundle único.
3. Criar `resources/css/variables.css` com os tokens de design (`--fm-*`
   ou equivalente) **primeiro**, antes de escrever qualquer componente —
   tudo o resto deve consumir as variáveis.
4. Definir a convenção de nomes CSS (prefixo próprio + BEM-like) e
   documentá-la num comentário no topo do `app.css`.
5. Se precisar de um painel administrativo separado do login de
   clientes: adicionar um segundo guard em `config/auth.php` com o seu
   próprio modelo/tabela, e um grupo de rotas `/admin` com middleware
   dedicado — não misturar com o guard `web` nem usar um pacote de admin
   pronto se o objetivo é manter controlo total do design.
6. Escrever o `DEPLOY.md` **enquanto se configura o hosting pela primeira
   vez**, não depois — é o momento em que todos os detalhes chatos
   (caminhos relativos, versão do PHP, links simbólicos) estão frescos.
7. Criar o `deploy.sh` no servidor assim que o primeiro deploy manual
   funcionar, para os seguintes serem um comando só.
8. Para uploads de utilizador, usar sempre `Storage::disk(...)->store()`
   sem forçar nome de ficheiro (nomes aleatórios evitam problemas de
   cache); para assets de design fixos, guardar em `public/` versionado
   no git.
9. Seeders de dados iniciais: usar `firstOrCreate`/`create` condicional,
   nunca `updateOrCreate` sobre a tabela toda, assim que a aplicação
   estiver em produção com dados reais a poderem ser editados por
   utilizadores.

---

## 12. O que este projeto **não** usa (para não presumir por defeito)

- Sem TypeScript, sem framework JS (React/Vue/Alpine), sem jQuery.
- Sem Tailwind ou qualquer CSS-in-JS.
- Sem Docker/containers — tudo corre diretamente no PHP do hosting.
- Sem filas/jobs em background, sem Redis, sem broadcasting em tempo
  real.
- Sem testes automatizados (`tests/` existe por defeito do Laravel mas
  não está a ser usado ativamente neste projeto).
- Sem gateway de pagamento externo (Stripe/PayPal/Multicaixa Express) —
  pagamento é só uma etiqueta escolhida pelo cliente, geno admin.
- Sem CDN/serviço de imagens externo (Cloudinary, etc.) — tudo servido
  diretamente do próprio hosting.
