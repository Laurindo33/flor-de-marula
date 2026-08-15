# Deploy — Hostinger (Web Premium ou superior)

Guia para colocar o Flor de Marula em produção na hospedagem partilhada do
Hostinger. Assume um plano com SSH + Composer (Premium Web ou superior).

## 1. Pré-requisitos no hPanel

- [ ] PHP **8.3** selecionado para o domínio (hPanel → Websites → Dashboard →
      PHP Configuration).
- [ ] Acesso SSH ativado (hPanel → Advanced → SSH Access).
- [ ] Base de dados MySQL criada (hPanel → Databases → MySQL Databases) —
      anotar nome da base, utilizador e password gerados.
- [ ] Domínio apontado para o Hostinger (DNS já propagado) — **não aplicável**
      se for usar o domínio temporário `xxxxxxx.hostingersite.com` que o
      Hostinger já dá automaticamente; nesse caso o `APP_URL` no `.env` usa
      esse domínio temporário e não é preciso mexer em DNS nenhum.

## 2. Document root

**Correção importante:** nos planos partilhados do Hostinger (Web/Cloud), o
document root **não pode ser alterado** — fica sempre fixo em `public_html`.
Não é possível apontá-lo diretamente para a pasta `public/` do Laravel como
seria o ideal.

A solução padrão e já testada para este cenário: o projeto Laravel fica
**fora** de `public_html` (numa pasta privada, ex.: `~/flor-de-marula`), e
dentro de `public_html` ficam apenas o `index.php` (copiado e com os
caminhos ajustados), o `.htaccess`, e **links simbólicos** para as pastas
estáticas (`build/`, `storage/`, `images/`) — assim, `git pull` +
`npm run build` em deploys seguintes refletem automaticamente sem copiar
nada de novo.

## 3. Primeiro deploy (via SSH)

```bash
ssh <utilizador>@<servidor> -p 65002   # comando exato copiado do "SSH Command" no hPanel

cd ~
git clone <url-do-repo> flor-de-marula
cd flor-de-marula

composer2 install --no-dev --optimize-autoloader

cp .env.example .env
# editar o .env com um editor disponível (nano/vi) — ver secção 4 abaixo
php artisan key:generate

npm ci
npm run build
php artisan migrate --force

# Ligar a pasta publica (public_html) a este projeto
cd ~
rm -rf public_html/*          # cuidado: apaga o que la estiver (ex.: pagina antiga); fazer backup antes se precisar
cp flor-de-marula/public/index.php public_html/index.php
cp flor-de-marula/public/.htaccess public_html/.htaccess
ln -s ~/flor-de-marula/public/build public_html/build
ln -s ~/flor-de-marula/public/images public_html/images
ln -s ~/flor-de-marula/storage/app/public public_html/storage

# Ajustar os caminhos no index.php copiado (aponta para vendor/ e bootstrap/
# um nivel acima de public_html, dentro de flor-de-marula/)
sed -i "s#__DIR__.'/\.\./vendor/autoload\.php'#__DIR__.'/../flor-de-marula/vendor/autoload.php'#" public_html/index.php
sed -i "s#__DIR__.'/\.\./bootstrap/app\.php'#__DIR__.'/../flor-de-marula/bootstrap/app.php'#" public_html/index.php
sed -i "s#__DIR__.'/\.\./storage/framework/maintenance\.php'#__DIR__.'/../flor-de-marula/storage/framework/maintenance.php'#" public_html/index.php

# Conferir se os 3 caminhos foram mesmo alterados antes de continuar:
cat public_html/index.php

php artisan config:cache
php artisan route:cache
php artisan view:cache
```

Não usar `php artisan storage:link` aqui — ele criaria o link dentro de
`flor-de-marula/public/storage`, que não é servido. O link já foi criado
manualmente acima, direto em `public_html/storage`.

## 4. `.env` de produção — diferenças do `.env.example`

| Variável | Valor em produção | Motivo |
|---|---|---|
| `APP_ENV` | `production` | |
| `APP_DEBUG` | `false` | **Crítico** — com `true`, erros expõem stack traces, caminhos de ficheiro e queries SQL publicamente. |
| `APP_URL` | `https://flordemarula.com` (domínio real) | |
| `DB_*` | credenciais da base criada no passo 1 | |
| `MAIL_MAILER` | `smtp` | Em `log` (padrão local) os e-mails nunca saem — nem confirmação de pedido chega ao cliente. Usar o SMTP do Hostinger (Emails → Email Accounts) ou um serviço como Resend/Mailgun. |
| `MAIL_HOST` / `MAIL_PORT` / `MAIL_USERNAME` / `MAIL_PASSWORD` | credenciais reais do SMTP escolhido | |
| `LOG_LEVEL` | `error` (opcional) | Reduz ruído nos logs em produção. |

## 5. Cron (para o futuro)

O projeto não usa `Schedule::` nem jobs em fila hoje, mas é boa prática
já deixar o cron configurado (hPanel → Advanced → Cron Jobs):

```
* * * * * cd ~/flor-de-marula && php artisan schedule:run >> /dev/null 2>&1
```

Se um dia forem adicionados jobs (`ShouldQueue`), hospedagem partilhada não
permite um `queue:work` permanente — em vez disso, adicionar um cron
separado a cada minuto rodando `php artisan queue:work --stop-when-empty`.

## 6. Deploys seguintes (atualizar código)

```bash
cd ~/flor-de-marula
git pull
composer2 install --no-dev --optimize-autoloader
npm ci && npm run build
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

Não é preciso repetir os passos de ligação a `public_html` (secção 3) —
como `build/`, `images/` e `storage/` são links simbólicos, o conteúdo novo
aparece automaticamente. Só repetir esses passos se algum ficheiro estático
novo for adicionado fora dessas pastas.

## 7. Pós-deploy

- [ ] Ativar SSL grátis (Let's Encrypt) — hPanel → SSL, um clique.
- [ ] Confirmar que os backups automáticos da base de dados estão ativos
      (hPanel → Files → Backups).
- [ ] Testar o fluxo de checkout completo (inclui envio de e-mail — ver
      secção 4) e o login do `/admin`.
- [ ] Trocar a password do admin seedado (`AdminSeeder`) por uma nova, se
      ainda não foi feito.
