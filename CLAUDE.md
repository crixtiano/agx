# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## What this is

Site institucional da **AGX, Associação Guanambiense de Xadrez** (https://www.agx.org.br/). É um site PHP renderizado no servidor, sem framework, sem etapa de build e sem gerenciador de dependências. Bootstrap 5 e Bootstrap Icons são carregados via CDN; a fonte Outfit fica no próprio site (`app/includes/fonts/`), sem Google Fonts, para não repassar o IP do visitante ao Google (LGPD).

O site antigo (página estática no GitHub Pages, https://crixtiano.github.io/agx/) está arquivado na tag git `pre-site-2025`.

A área do associado, a inscrição e os documentos ficam no Galette (https://inscritos.agx.org.br), uma aplicação separada. O site só aponta para ela (e lê de lá a lista pública de documentos).

## Running locally

```bash
cd app
php -S 127.0.0.1:8000
```

Abrir http://127.0.0.1:8000/index.php.

## Directory structure

```
agx/
├── CLAUDE.md
├── .gitignore
├── cache/                # gerado pelo site, ignorado no git
├── docs/
│   └── mockups/          # layout aprovado (.dc.html), só referência, fora do site
└── app/                  # raiz pública do site
    ├── .htaccess         # Options -Indexes (só vale se o servidor permitir AllowOverride)
    ├── favicon.ico       # ícone da aba (fica na raiz: muitos programas procuram /favicon.ico)
    ├── index.php         # monta todas as páginas
    ├── lib/
    │   ├── lang.php      # t(), getLang(), setLang(), htmlLang()
    │   ├── galette.php   # documentos públicos do Galette (Transparência)
    │   └── lang/         # pt_BR.php, en_US.php
    ├── includes/
    │   ├── top.php       # <head>, cabeçalho e menu
    │   ├── bottom.php    # rodapé e scripts
    │   ├── css/style.css # inclui o @font-face da Outfit
    │   ├── fonts/        # Outfit (woff2 variável, subsets latin e latin-ext) + OFL.txt
    │   └── js/main.js
    ├── paginas/          # páginas do meio
    └── imgs/             # logos e prints do pré-cadastro (favicon: `app/favicon.ico` com 16/24/32/48 px para a aba, icone512x512.png para Google e telas grandes, apple-touch-icon.png 180 × 180 com fundo sólido para a tela inicial do iPhone)
```

## Architecture

### Page assembly

Toda requisição passa por `app/index.php`, que monta a página assim:

```
includes/top.php  +  paginas/<página>.php  +  includes/bottom.php
```

`top.php` abre `<html>`, `<body>` e `<main>`; `bottom.php` fecha. Sempre incluir os dois.

Variáveis definidas por `index.php` antes dos includes:

| Variável | Uso |
|---|---|
| `$page_title` | Nome traduzido da página; `''` na página inicial. O título fica "AGX — {$page_title}". |
| `$nav_ativo` | Chave da página ativa, para destacar o item do menu. |

Links internos sempre via `pageUrl('chave')` ou `pageUrl('chave', 'ancora')`, nunca com o endereço escrito à mão.

### Page whitelist

A página do meio vem de `?p=` (ex.: `index.php?p=transparencia`; sem `p`, abre `inicio`).

- O valor de `p` é usado **apenas como chave** do array `$paginas` em `index.php`. O arquivo incluído sempre vem dessa lista, nunca da URL.
- Chave fora da lista: `index.php` responde HTTP 404 e mostra `paginas/404.php`.
- Para criar uma página: criar `paginas/<nome>.php`, adicionar a entrada em `$paginas` e, se for item de menu, em `$menu` (`includes/top.php`), mais as chaves de tradução nos dois dicionários.
- Includes e páginas começam com `defined('AGX_APP') || exit;`, para não serem abertos direto pela URL.

| Chave | Arquivo | Menu |
|---|---|---|
| `inicio` | `inicio.php` | logo |
| `quem-somos` | `quem-somos.php` | Quem somos |
| `dirigentes` | `dirigentes.php` | Dirigentes |
| `transparencia` | `transparencia.php` | Transparência |
| `agenda` | `agenda.php` | Agenda |
| `contato` | `contato.php` | Contato |
| `contribua` | `contribua.php` | Contribua (botão de destaque) |
| `pre-cadastro` | `pre-cadastro.php` | fora do menu (veja "Pré-cadastro") |

`pageUrl()` aceita parâmetros extras: `pageUrl('pre-cadastro', '', ['associar' => 'sim'])`.

### Site configuration

Constantes no topo de `app/index.php`: `AGX_CNPJ`, `AGX_EMAIL`, `AGX_INSTAGRAM`, `AGX_FACEBOOK`, `GALETTE_URL`, `GALETTE_DOCS_TTL`, `SALARIO_MINIMO` e `SALARIO_MINIMO_ANO`.

### Internationalisation

Idioma padrão `pt_BR`; `en_US` também é suportado. O idioma vem da URL, não da sessão: `?lang=en` é inglês; sem `lang` (ou qualquer outro valor) é português. Assim cada idioma tem endereços próprios, que o Google indexa separadamente.

- `pageUrl()` acrescenta `lang=en` sozinho quando a página está em inglês, então todo link interno mantém o idioma. O 4º parâmetro força um idioma: `pageUrl('agenda', '', [], 'en_US')`.
- `currentPageUrl($lang, $absoluto)` (`index.php`) dá o endereço da página atual no idioma pedido. É usado pelos links **PT | EN** do menu e pelas tags `canonical` e `hreflang` (pt-BR, en, x-default = pt) do `<head>`, que não aparecem na página 404.

- Dicionários em `app/lib/lang/pt_BR.php` e `app/lib/lang/en_US.php`, retornando arrays de chaves em notação de ponto.
- Usar `t('chave')` em todo texto de interface; placeholders no formato `:nome` (`t('footer.rights', ['year' => 2026])`).
- Chave sem tradução em `en_US` cai para `pt_BR`; sem nenhuma, aparece a própria chave.
- Strings dos dicionários são confiáveis e podem conter HTML (links, `<strong>`); dados de fora (nomes etc.) passam por `htmlspecialchars`.

## Layout

Layout aprovado: "Modelo 3 — Portal da Comunidade". Referência visual e de conteúdo em `docs/mockups/` (`Modelo3.dc.html`, `Contribua.dc.html`, `Transparencia.dc.html`). São arquivos de um editor de design (dependem de um `support.js` que não temos), servem só de referência.

### Decisões de layout (refinadas com o usuário)

- **Cabeçalho:** logo AGX (≈44px de altura) à esquerda com "Associação Guanambiense de Xadrez" em letra pequena **embaixo** do logo; menu alinhado à **direita** na mesma linha. Sem ícones de redes sociais no cabeçalho (ficam em Contato e no rodapé). Idioma no próprio menu como links discretos **PT | EN** (ativo em negrito). Logo depois, o ícone de pessoa (`bi-person`) leva à área do associado no Galette, `GALETTE_URL/?ui_pref_lang=` + idioma ativo (pt_BR ou en_US); no menu do celular o ícone vem com o texto "Área do associado". Em telas pequenas, menu hambúrguer do Bootstrap (navbar collapse).
- **Menu:** Quem somos · Dirigentes · Transparência · Agenda · Contato · **Contribua** (botão de destaque). Cada item é uma página própria.
- **Página inicial:** faixa branca de ponta a ponta com o **logo grande centralizado** (até ~760px) e, abaixo, o nome da associação em caixa alta espaçada. Logo abaixo, uma faixa azul-marinho de ponta a ponta (mesma altura das faixas das páginas internas) com um traço amarelo, o título "Vamos juntos colocar Guanambi no mapa do xadrez!" e a frase "Aulas, torneios, xadrez nas escolas e encontros abertos à comunidade.", centralizados. O título diminui em telas menores (42 / 38 / 34 / 30px) para caber nessa altura. Abaixo da faixa, os botões "Associe-se" (→ pré-cadastro, variação "sim") e "Conheça a AGX" (→ Quem somos). Abaixo, os cards "Próximo evento" e "Já é associado?" e o bloco de doação.
- **Páginas internas:** faixa azul-marinho com caminho (Início / Página), título e texto de abertura. A primeira seção abaixo da faixa usa `agx-section-sm` (32px); as seções seguintes usam `agx-section` (56px).
- **Altura da faixa azul:** igual em todas as páginas (inclusive a inicial), com o conteúdo alinhado ao topo. É um `min-height` por faixa de tela em `.agx-pagehead .agx-wrap` e `.agx-hero-band .agx-wrap` (248px ≥992, 268px ≥768, 296px ≥576, 334px no celular), igual à faixa mais alta medida em cada tamanho (pt_BR e en_US). Ao aumentar um título ou texto de abertura, medir de novo. Por isso a faixa só tem caminho, título e texto de abertura; na Contribua, os atalhos (Associe-se, Doe…) ficam logo abaixo dela.
- **Dirigentes:** três blocos empilhados (Diretoria, Conselho Fiscal, Assessoria Jurídica), cada um com seu subtítulo; cards do mesmo tamanho.
- **Sem endereço físico no site.**

### Páginas

| Página | Conteúdo |
|---|---|
| Início | Hero, "Próximo evento", "Já é associado?", bloco de doação |
| Quem somos | Apresentação, 6 cards de áreas de atuação, "Como funcionamos" |
| Dirigentes | Diretoria, Conselho Fiscal, Assessoria Jurídica |
| Transparência | Tabela de documentos lida do Galette, com filtro por tipo |
| Agenda | Lista de eventos (hoje: estado vazio) |
| Contato | Instagram, Facebook, E-mail |
| Contribua | Associe-se (planos), categorias de associado, Doe (Pix), cadastro de atleta, voluntário, parceiro |
| Pré-cadastro | Explica a opção "Desejo ser membro associado" antes do formulário de inscrição |

### Pré-cadastro

Todo link de inscrição passa por `index.php?p=pre-cadastro&associar=sim|nao` antes do formulário externo (`AGX_SUBSCRIBE_URL`), nunca direto para ele. "Pré-cadastro" é só o nome interno; o visitante nunca vê esse termo.

Cada variação tem seu título (aba do navegador, caminho e `<h1>`), escolhido em `index.php` via `presignupVariant()`:

| Variação | pt_BR | en_US |
|---|---|---|
| `sim` | Como se cadastrar e se associar à AGX | How to sign up and become an AGX member |
| `nao` | Como se cadastrar sem se associar | How to sign up without becoming a member |

| Variação | Quem chega | Aviso | Imagem |
|---|---|---|---|
| `sim` | "Associe-se" (início) e "Quero me associar" (Contribua) | marcar "Desejo ser membro associado" | `imgs/associar_sim.jpg` |
| `nao` | cadastro de atleta, árbitro ou instrutor (Contribua) | não precisa marcar a opção | `imgs/associar_nao.jpg` |

- Layout: mensagem centralizada no topo; abaixo, print à esquerda e botão do formulário à direita (centralizado na vertical). No celular (< 768px) empilha mensagem, print, botão.
- O print fica numa moldura cinza com a legenda "Exemplo: como a opção aparece no formulário", para não ser confundido com o formulário de verdade.
- Clicar no print abre um modal do Bootstrap ("Esta é apenas uma imagem de exemplo") com os botões "Entendi" (fecha) e "Ir para o formulário". Abre por `data-bs-toggle`, sem JavaScript próprio.
- Qualquer valor de `associar` diferente de `sim` (ou ausente) cai em `nao`.
- O link do formulário usa `ui_pref_lang` igual ao idioma ativo do site.
- Os prints estão em português; a versão en_US cita a opção pelo nome em português com a tradução entre parênteses.

### Identidade visual

Tokens definidos em `:root` de `app/includes/css/style.css`.

| Uso | Valor |
|---|---|
| Fonte | **Outfit**, pesos 400/500/600/700 |
| Primária (azul-marinho) | `#1B2A4A` |
| Destaque (amarelo) | `#F2C230` |
| Fundo | `#F3F5F9` |
| Texto / secundário | `#172033` / `#4B5568` |
| Bordas | `#E2E6EE` / `#CBD2DE` |
| Links | `#1D4ED8` |

- Cards brancos com cantos arredondados (20–24px).
- Ícones em traço (stroke SVG), sem emoji.
- Instagram, Facebook e e-mail usam os ícones do Bootstrap Icons (`bi-instagram`, `bi-facebook`, `bi-envelope`, carregado via CDN em `top.php`). Links só com ícone (rodapé, início, agenda) levam `aria-label` e `title` com o nome do destino.
- Separador de seção: linha fina `border-top: 1px solid #CBD2DE` antes dos títulos.
- Pendente: o logo é azul-marinho + prata; considerar trocar o amarelo por um tom do próprio logo.

## Links fixos

| Destino | Endereço |
|---|---|
| Facebook | https://www.facebook.com/agx.org.br/ |
| Instagram | https://www.instagram.com/somosagx/ (@somosagx) |
| E-mail | contato@agx.org.br |
| Área do associado / login (ícone do cabeçalho, "Entrar na área do associado", "Já sou associado — entrar") | https://inscritos.agx.org.br/?ui_pref_lang=pt_BR (ou `en_US`) |
| Formulário de inscrição (via pré-cadastro) | https://inscritos.agx.org.br/subscribe?ui_pref_lang=pt_BR (ou `en_US`) |

Todo link para o Galette é montado com `galetteUrl('/caminho')` (`app/index.php`), que acrescenta `?ui_pref_lang=` com o idioma ativo do site (pt_BR ou en_US). Nunca escrever o endereço do Galette com o idioma fixo.

## Conteúdo institucional

Fonte: Estatuto Social, doc. 2026-05-21-03-ES. A cópia pública (com dados pessoais ocultados, LGPD) está no Galette, em Transparência; o site não guarda mais o PDF.

- Associação civil sem fins lucrativos, fundada em 21/05/2026, de caráter esportivo, educacional, cultural, tecnológico e social. CNPJ 69.466.981/0001-63.
- Frentes (Art. 4º): xadrez na escola; esporte e competição (talentos, ranking, rating, torneios presenciais/online); inclusão social; saúde e envelhecimento ativo; xadrez digital; cultura e comunidade.
- **Cards de Quem somos** (não repetem exatamente o Art. 4º; "Xadrez digital" e "Saúde e envelhecimento ativo" saíram do site), nesta ordem, com o rodapé de cada card:

  | Card | Chave | Rodapé |
  |---|---|---|
  | Esporte e competição | `about.f2` | link "Ver agenda" |
  | Xadrez na escola | `about.f1` | "Em breve." |
  | Cultura e comunidade | `about.f6` | link "Ver agenda" |
  | Intercâmbio e desenvolvimento regional | `about.f5` | "Em breve." |
  | Parcerias institucionais | `about.f4` | link "Quer ser parceiro? Fale conosco →" (Contato) |
  | Inclusão social | `about.f3` | (nenhum) |

  Ordem, ícones e rodapés ficam em `$frentes` e `$rodape` (`app/paginas/quem-somos.php`); textos nos dicionários.
- Governança: Assembleia Geral soberana; Diretoria e Conselho Fiscal eleitos por voto secreto, mandato de 3 anos (Art. 21); cargos eletivos sem remuneração (Art. 27); vedada distribuição de lucro (Art. 15).

### Dirigentes

Dados em `app/paginas/dirigentes.php` (nomes) e nos dicionários (cargos).

| Grupo | Cargo | Nome |
|---|---|---|
| Diretoria Executiva | Presidente | Cristiano Meira Magalhães |
| | Vice-Presidente | Israel Garcês Pedrosa |
| | Secretário | Diesley da Silva Fernandes |
| | Tesoureiro | Tarcísio Meira de Oliveira |
| Conselho Fiscal | Conselheiro | Tarcísio Oliveira Teles |
| | Conselheiro | Luciano Bismark Ferreira Pereira |
| | Conselheiro | Hadson Silva Lima |
| | Suplente | Joseberto Ribeiro Cruz |
| Assessoria Jurídica | Advogado | Claudio Oton Teixeira Fernandes |

Mandato da Diretoria e do Conselho Fiscal: 21/05/2026 (Assembleia de Fundação) a 21/05/2029, mostrado no texto de abertura da página (`board.lead`).

Pendente: fotos (hoje os cards mostram as iniciais).

### Associação (página Contribua)

- Categorias (Art. 5º): Fundador Benemérito, Fundador, Efetivo, Honorário. Novos associados entram como Efetivo.
- Mensalidade (Art. 8º §5): 1% do salário mínimo vigente, arredondada para cima ao múltiplo de R$ 5. Anuidade = 10 mensalidades (Art. 15 §4). Calculadas em `paginas/contribua.php` a partir de `SALARIO_MINIMO`; 2026: R$ 1.621 → **R$ 20/mês** e **R$ 200/ano**. Atualizar só `SALARIO_MINIMO` e `SALARIO_MINIMO_ANO` a cada ano.
- Somente maiores de 18 anos (Art. 10). Admissão por proposta analisada pela Diretoria (Art. 6º).
- Cadastro de atleta (Art. 11): gratuito, qualquer idade (menores com autorização do responsável), obrigatório para torneios e ranking, não gera vínculo associativo.
- Cadastros de árbitro e instrutor (Arts. 12 e 13): gratuitos, sem vínculo associativo.
- Doação: Pix chave CNPJ 69.466.981/0001-63; recibo sob solicitação; relatórios públicos sem dados pessoais de doadores.

### Transparência

A tabela de Documentos vem do Galette: `app/lib/galette.php` lê a página pública https://inscritos.agx.org.br/public/documents e a página mostra o que vier de lá. Para publicar, remover ou mudar um documento, use o Galette; não há lista no código.

- **Filtros:** os botões são os **tipos de documento** cadastrados no Galette (hoje: "Institucional"), na ordem do Galette, mais "Todos".
- **Colunas:** Documento (descrição do Galette, com o nome do arquivo embaixo), Tipo, Publicado em (data de envio ao Galette, não a data do documento) e Arquivo (link `/document/get/<id>` no Galette, que baixa sem login).
- **Sem API:** o Galette não tem API para isso, então o HTML é lido com expressões regulares (sem depender da extensão DOM). Se uma atualização do Galette mudar o HTML da página, a leitura falha: o site continua mostrando a última lista do cache e grava o erro no log do PHP (`AGX galette: ...`).
- **Cache:** `agx/cache/galette_documents.json` (fora de `app/`, ignorado no git), válido por `GALETTE_DOCS_TTL` segundos (900 = 15 min). Galette fora do ar: mostra o cache antigo e tenta de novo após outro TTL; sem cache, mostra aviso com link para a página do Galette. Para forçar atualização, apague o arquivo. O servidor precisa poder gravar em `agx/cache/`.
- **Paginação:** hoje a lista pública do Galette não é paginada (o código do Galette carrega todos os documentos públicos de uma vez). Se aparecerem links `/public/documents/page/N`, o código os segue (até 20 páginas), sem duplicar documentos.

Exigências do estatuto ainda não atendidas:

- Art. 18: portal com relatórios, atas, balanços **e contratos**.
- Art. 37: publicar as políticas internas: Proteção de Dados (LGPD); Proteção à Infância e Juventude; Código de Ética e Conduta; Antifraude e Anticorrupção; Integridade em Eventos Online e Presenciais; Contratações e Chamamentos.
- Art. 38: canal de denúncia com confidencialidade.
- Art. 42: documentos disponíveis e arquivados por no mínimo 5 anos.

## Roadmap

### Agenda / eventos

Hoje não há eventos: a página mostra o estado vazio ("Nenhum evento agendado no momento" + link do Instagram).

Recomendação: tabela `eventos` no PostgreSQL, cadastrada pelo painel administrativo do `/associado` (campos: título, tipo, início, fim, local, descrição, valor sócio/não sócio, vagas, link de inscrição, link de resultados, publicado). O site lê do banco e também gera um feed `.ics` para assinatura. Alternativa de curto prazo: Google Agenda pública lida via `.ics` com cache. Escrever a camada de leitura de eventos atrás de uma interface, para trocar a fonte depois sem mexer nas páginas.

### Documentos

Aplicação para guardar e publicar os documentos da Transparência (upload, tipo, data, visibilidade pública/interna), alimentando a tabela da página automaticamente.
