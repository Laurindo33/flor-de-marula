# Deploy — Hostinger (Web Premium ou superior)

## Estado atual

O site já está no ar num domínio de teste, para validação antes de decidir
o que fazer com `flordemarula.com` (que hoje tem a página do Atomicat):

- **URL**: https://flordemarula.cromavision.online
- **Servidor**: `u368551644@82.198.228.49` (porta SSH `65002`)
- **Projeto**: `~/flor-de-marula` (repositório git, sincronizado com o
  GitHub via uma **deploy key** só de leitura — ver secção 6)
- **PHP em uso**: **8.4** (não 8.3 — o `composer.lock` deste projeto exige
  `symfony/*` v8.1, que precisa de PHP ≥8.4.1). No servidor, o binário fica
  em `/opt/alt/php84/usr/bin/php`; confirmar que o domínio está configurado
  para PHP 8.4 em hPanel → PHP Configuration.
- **Node.js**: **não existe no servidor** (comum em hosting partilhado). Os
  assets do frontend (`public/build/`) são compilados localmente
  (`npm run build`) e enviados já prontos — nunca rodar `npm` no servidor.

## 1. Pré-requisitos no hPanel

- [x] PHP **8.4** selecionado para o domínio.
- [x] Acesso SSH ativado.
- [x] Base de dados MySQL criada.
- [x] Deploy key do GitHub adicionada (repo → Settings → Deploy keys).

## 2. Document root

Nos planos partilhados do Hostinger (Web/Cloud), o document root **não pode
ser alterado** — fica sempre fixo em `public_html`. Não é possível apontá-lo
diretamente para a pasta `public/` do Laravel.

Solução usada: o projeto Laravel fica **fora** de `public_html`, em
`~/flor-de-marula`. Dentro de `public_html` ficam apenas o `index.php`
(copiado e com os caminhos ajustados) e o `.htaccess`, mais **links
simbólicos** para as pastas estáticas:

```
public_html/
├── index.php          (copia de flor-de-marula/public/index.php, caminhos ajustados)
├── .htaccess           (copia de flor-de-marula/public/.htaccess)
├── build   -> ~/flor-de-marula/public/build
├── images  -> ~/flor-de-marula/public/images
├── videos  -> ~/flor-de-marula/public/videos
└── storage -> ~/flor-de-marula/storage/app/public
```

Como são links simbólicos, `git pull` + rebuild dos assets refletem
automaticamente — não é preciso repetir esta ligação em cada deploy, só se
uma pasta estática **nova** for criada fora das já linkadas.

**Atenção ao caminho relativo do `index.php`**: `public_html` fica em
`~/domains/<dominio>/public_html`, ou seja, **3 níveis** abaixo do home
(`../../../flor-de-marula/...`), não 2. Conferir com `pwd` antes de montar
os caminhos se for repetir isto para outro domínio.

## 3. Primeiro deploy noutro domínio/site (do zero)

```bash
ssh <utilizador>@<servidor> -p <porta>

# Gerar uma deploy key dedicada e adicionar em GitHub → repo → Settings → Deploy keys
ssh-keygen -t ed25519 -f ~/.ssh/github_deploy_key -N "" -C "flor-de-marula-deploy"
cat ~/.ssh/github_deploy_key.pub   # colar no GitHub

cat > ~/.ssh/config <<'EOF'
Host github.com
    HostName github.com
    User git
    IdentityFile ~/.ssh/github_deploy_key
    IdentitiesOnly yes
EOF
chmod 600 ~/.ssh/config

cd ~
git clone git@github.com:Laurindo33/flor-de-marula.git flor-de-marula
cd flor-de-marula

/opt/alt/php84/usr/bin/php /usr/local/bin/composer2 install --no-dev --optimize-autoloader

cp .env.example .env
# editar o .env manualmente (nano/vi) — ver secção 4
/opt/alt/php84/usr/bin/php artisan key:generate --force

mkdir -p storage/framework/views storage/framework/cache/data storage/framework/sessions storage/logs
chmod -R 775 storage bootstrap/cache

/opt/alt/php84/usr/bin/php artisan migrate --force
/opt/alt/php84/usr/bin/php artisan db:seed --force   # opcional, só para popular dados de exemplo
```

**Assets do frontend** (fora do SSH, na tua máquina local):

```bash
npm ci
npm run build
scp -P <porta> -r public/build/* <utilizador>@<servidor>:~/flor-de-marula/public/build/
```

**Ligar a `public_html`** (ajustar `<dominio>` e conferir a profundidade
real do caminho com `pwd`, ver aviso na secção 2):

```bash
cd ~/domains/<dominio>
rm -rf public_html/*
cp ~/flor-de-marula/public/index.php public_html/index.php
cp ~/flor-de-marula/public/.htaccess public_html/.htaccess
ln -s ~/flor-de-marula/public/build public_html/build
ln -s ~/flor-de-marula/public/images public_html/images
ln -s ~/flor-de-marula/public/videos public_html/videos
ln -s ~/flor-de-marula/storage/app/public public_html/storage

sed -i "s#__DIR__.'/\.\./vendor/autoload\.php'#__DIR__.'/../../flor-de-marula/vendor/autoload.php'#" public_html/index.php
sed -i "s#__DIR__.'/\.\./bootstrap/app\.php'#__DIR__.'/../../flor-de-marula/bootstrap/app.php'#" public_html/index.php
sed -i "s#__DIR__.'/\.\./storage/framework/maintenance\.php'#__DIR__.'/../../flor-de-marula/storage/framework/maintenance.php'#" public_html/index.php
cat public_html/index.php   # conferir os 3 caminhos antes de continuar
```

Não usar `php artisan storage:link` — ele criaria o link dentro de
`flor-de-marula/public/storage`, que não é servido. O link certo já foi
criado acima, direto em `public_html/storage`.

```bash
cd ~/flor-de-marula
/opt/alt/php84/usr/bin/php artisan config:cache
/opt/alt/php84/usr/bin/php artisan route:cache
/opt/alt/php84/usr/bin/php artisan view:cache
```

**Ordem importa**: criar as pastas `storage/framework/*` (acima) **antes**
de rodar `config:cache` — o cache guarda `realpath(storage_path('framework/views'))`,
que resolve para `false` se a pasta ainda não existir nesse momento, e o
`view:cache` falha com "View path not found" até se limpar e recachear.

## 4. `.env` de produção — diferenças do `.env.example`

| Variável | Valor em produção | Motivo |
|---|---|---|
| `APP_ENV` | `production` | |
| `APP_DEBUG` | `false` | **Crítico** — com `true`, erros expõem stack traces, caminhos de ficheiro e queries SQL publicamente. |
| `APP_URL` | `https://<dominio>` | |
| `DB_HOST` | `localhost` | |
| `DB_DATABASE` / `DB_USERNAME` / `DB_PASSWORD` | credenciais da base criada em hPanel → Databases | O utilizador já vem prefixado (`uXXXXXXXX_...`). |
| `MAIL_MAILER` | `smtp` | Em `log` (padrão local) os e-mails nunca saem — nem confirmação de pedido chega ao cliente. Usar o SMTP do Hostinger (Emails → Email Accounts) ou um serviço como Resend/Mailgun. |
| `MAIL_HOST` / `MAIL_PORT` / `MAIL_USERNAME` / `MAIL_PASSWORD` | credenciais reais do SMTP escolhido | |
| `LOG_LEVEL` | `error` (opcional) | Reduz ruído nos logs em produção. |

## 5. Deploys seguintes (atualizar código)

No servidor já existe `~/flor-de-marula/deploy.sh` que faz os passos 1-4
de uma vez (git pull, composer, migrate, cache):

```bash
ssh <utilizador>@<servidor> -p <porta>
~/flor-de-marula/deploy.sh
```

Se algum ficheiro `.css`/`.js` mudou, compilar e enviar os assets à parte
**antes** de rodar o script (o servidor não tem Node.js):

```bash
npm run build
scp -P <porta> -r public/build/* <utilizador>@<servidor>:~/flor-de-marula/public/build/
```

## 6. Deploy key do GitHub

A ligação `git pull` no servidor usa uma chave SSH dedicada, só de leitura,
cadastrada como **Deploy key** no repositório (não uma chave de conta
pessoal) — assim, se o servidor for comprometido, o acesso revogável é só
a esse repositório, não à conta GitHub inteira. Para revogar: GitHub →
repo → Settings → Deploy keys → Delete.

## 7. Cron (para o futuro)

O projeto não usa `Schedule::` nem jobs em fila hoje, mas é boa prática
já deixar o cron configurado (hPanel → Advanced → Cron Jobs):

```
* * * * * cd ~/flor-de-marula && /opt/alt/php84/usr/bin/php artisan schedule:run >> /dev/null 2>&1
```

Se um dia forem adicionados jobs (`ShouldQueue`), hospedagem partilhada não
permite um `queue:work` permanente — em vez disso, adicionar um cron
separado a cada minuto rodando `php artisan queue:work --stop-when-empty`.

## 8. Pós-deploy / antes de ir para o domínio real

- [ ] Ativar SSL grátis (Let's Encrypt) — hPanel → SSL, um clique.
- [ ] Confirmar que os backups automáticos da base de dados estão ativos.
- [ ] Configurar `MAIL_MAILER=smtp` com credenciais reais (ver secção 4) e
      testar o fluxo de checkout completo.
- [ ] Trocar a password do admin seedado (`AdminSeeder`:
      `admin@flordemarula.com` / `FlorMarula#Admin2026`) por uma nova.
- [ ] Quando decidido apontar `flordemarula.com` para cá (ou usar um
      subdomínio como `loja.flordemarula.com`), atualizar `APP_URL` no
      `.env` e rodar `config:cache` de novo.
- [ ] Remover a chave SSH pessoal usada para configurar tudo isto
      (hPanel → Advanced → SSH Access → Manage Public Keys) se não for
      mais precisa.
