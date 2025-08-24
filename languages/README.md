# Arquivos de Tradução - Simple Locator WooCommerce Filter

Este diretório contém os arquivos de tradução para o plugin Simple Locator WooCommerce Filter.

## 📁 Arquivos Disponíveis

### Template de Tradução

- `simple-locator-wc-filter.pot` - Arquivo template (POT) com todas as strings traduzíveis

### Traduções Compiladas

- `simple-locator-wc-filter-pt_BR.po` - Tradução para Português Brasileiro
- `simple-locator-wc-filter-en_US.po` - Tradução para Inglês Americano
- `simple-locator-wc-filter-es_ES.po` - Tradução para Espanhol (Espanha)

## 🔧 Como Usar

### Para Desenvolvedores

1. **Criar uma nova tradução:**

   ```bash
   # Copie o arquivo POT
   cp simple-locator-wc-filter.pot simple-locator-wc-filter-XX_XX.po

   # Edite o arquivo .po com suas traduções
   # Use um editor como Poedit ou similar
   ```

2. **Compilar a tradução:**

   ```bash
   # Instale o gettext se ainda não tiver
   # No Ubuntu/Debian: sudo apt-get install gettext
   # No macOS: brew install gettext

   # Compile o arquivo .po para .mo
   msgfmt simple-locator-wc-filter-XX_XX.po -o simple-locator-wc-filter-XX_XX.mo
   ```

### Para Usuários

1. **Instalar tradução:**

   - Faça upload dos arquivos `.mo` para este diretório
   - O WordPress detectará automaticamente as traduções

2. **Ativar tradução:**
   - Vá em **Configurações > Geral**
   - Selecione o idioma desejado
   - Salve as alterações

## 🌍 Idiomas Suportados

### Português Brasileiro (pt_BR)

- **Arquivo:** `simple-locator-wc-filter-pt_BR.po`
- **Status:** ✅ Completo
- **Tradutor:** David William da Costa

### Inglês Americano (en_US)

- **Arquivo:** `simple-locator-wc-filter-en_US.po`
- **Status:** ✅ Completo
- **Tradutor:** David William da Costa

### Espanhol (es_ES)

- **Arquivo:** `simple-locator-wc-filter-es_ES.po`
- **Status:** ✅ Completo
- **Tradutor:** David William da Costa

## 📝 Como Contribuir com Traduções

1. **Fork o repositório** no GitHub
2. **Crie um novo arquivo** `.po` baseado no template
3. **Traduza as strings** usando um editor como Poedit
4. **Compile o arquivo** para `.mo`
5. **Envie um Pull Request** com suas traduções

### Estrutura do Arquivo .po

```po
# Comentário explicativo
msgid "Texto original"
msgstr "Texto traduzido"
```

### Exemplo de Tradução

```po
#: simple-locator-woocommerce-filter.php:315
msgid "Ver no Mapa"
msgstr "View on Map"
```

## 🛠️ Ferramentas Recomendadas

### Editores de Tradução

- **Poedit** (Gratuito) - https://poedit.net/
- **Lokalise** (Online) - https://lokalise.com/
- **Crowdin** (Online) - https://crowdin.com/

### Validação

- **msgfmt** - Para compilar e validar arquivos .po
- **msgmerge** - Para atualizar traduções com novo template

## 📋 Checklist para Novas Traduções

- [ ] Copiar arquivo `simple-locator-wc-filter.pot`
- [ ] Renomear para `simple-locator-wc-filter-XX_XX.po`
- [ ] Atualizar cabeçalho com informações do tradutor
- [ ] Traduzir todas as strings
- [ ] Testar traduções no WordPress
- [ ] Compilar arquivo `.mo`
- [ ] Documentar no README

## 🔍 Strings Traduzíveis

O plugin inclui traduções para:

- **Mensagens de erro** e avisos
- **Labels de campos** no painel administrativo
- **Textos da interface** do usuário
- **Descrições** e tooltips
- **Mensagens de debug**

## 📞 Suporte

Para dúvidas sobre traduções:

1. Abra uma **Issue** no GitHub
2. Use a tag `[translation]` no título
3. Especifique o idioma e o problema

## 📄 Licença

As traduções seguem a mesma licença do plugin: GPL v2 ou posterior.
