# Sistema de Traduções - Simple Locator WooCommerce Filter

## 🌍 Visão Geral

O plugin Simple Locator WooCommerce Filter foi desenvolvido com suporte completo a internacionalização (i18n), permitindo que seja traduzido para qualquer idioma.

## 📁 Estrutura de Arquivos

```
languages/
├── simple-locator-wc-filter.pot          # Template de tradução
├── simple-locator-wc-filter-pt_BR.po     # Português Brasileiro (fonte)
├── simple-locator-wc-filter-pt_BR.mo     # Português Brasileiro (compilado)
├── simple-locator-wc-filter-en_US.po     # Inglês Americano (fonte)
├── simple-locator-wc-filter-en_US.mo     # Inglês Americano (compilado)
├── simple-locator-wc-filter-es_ES.po     # Espanhol (fonte)
├── simple-locator-wc-filter-es_ES.mo     # Espanhol (compilado)
├── compile-translations.sh               # Script de compilação
└── README.md                             # Documentação das traduções
```

## 🚀 Idiomas Disponíveis

### ✅ Português Brasileiro (pt_BR)

- **Status:** Completo
- **Tradutor:** David William da Costa
- **Arquivos:** `.po` e `.mo`

### ✅ Inglês Americano (en_US)

- **Status:** Completo
- **Tradutor:** David William da Costa
- **Arquivos:** `.po` e `.mo`

### ✅ Espanhol (es_ES)

- **Status:** Completo
- **Tradutor:** David William da Costa
- **Arquivos:** `.po` e `.mo`

## 🔧 Como Usar as Traduções

### Para Usuários Finais

1. **Instalação Automática:**

   - As traduções são incluídas no plugin
   - O WordPress detecta automaticamente os arquivos `.mo`
   - Não é necessária configuração adicional

2. **Ativar Tradução:**

   - Vá em **Configurações > Geral**
   - Selecione o idioma desejado
   - Salve as alterações

3. **Verificar Funcionamento:**
   - As mensagens do plugin aparecerão no idioma selecionado
   - Campos do painel administrativo serão traduzidos
   - Mensagens de erro e debug serão localizadas

### Para Desenvolvedores

#### Compilar Traduções Existentes

```bash
cd languages
./compile-translations.sh
```

#### Criar Nova Tradução

1. **Copiar template:**

   ```bash
   cp languages/simple-locator-wc-filter.pot languages/simple-locator-wc-filter-XX_XX.po
   ```

2. **Editar arquivo .po:**

   - Use um editor como Poedit
   - Traduza todas as strings
   - Salve o arquivo

3. **Compilar:**
   ```bash
   cd languages
   ./compile-translations.sh
   ```

## 📝 Strings Traduzíveis

O plugin inclui traduções para:

### Interface do Usuário

- Mensagens de erro e avisos
- Labels de botões e links
- Textos de informações
- Mensagens de debug

### Painel Administrativo

- Títulos de seções
- Labels de campos
- Descrições e tooltips
- Mensagens de configuração

### Exemplos de Strings

```php
// Mensagens de erro
__('Nenhum produto encontrado com os critérios especificados.', 'simple-locator-wc-filter')

// Labels de campos
__('Latitude', 'simple-locator-wc-filter')
__('Longitude', 'simple-locator-wc-filter')

// Botões
__('Ver no Mapa', 'simple-locator-wc-filter')
__('Ver Produto', 'simple-locator-wc-filter')
```

## 🛠️ Ferramentas de Tradução

### Editores Recomendados

1. **Poedit** (Gratuito)

   - Download: https://poedit.net/
   - Interface gráfica intuitiva
   - Suporte a múltiplos idiomas

2. **Lokalise** (Online)

   - URL: https://lokalise.com/
   - Colaboração em equipe
   - Integração com GitHub

3. **Crowdin** (Online)
   - URL: https://crowdin.com/
   - Plataforma profissional
   - Suporte a muitos formatos

### Validação de Arquivos

```bash
# Verificar sintaxe
msgfmt --check simple-locator-wc-filter-XX_XX.po

# Validar e compilar
msgfmt simple-locator-wc-filter-XX_XX.po -o simple-locator-wc-filter-XX_XX.mo
```

## 📋 Checklist para Novas Traduções

- [ ] Copiar arquivo `.pot` para novo idioma
- [ ] Atualizar cabeçalho com informações do tradutor
- [ ] Traduzir todas as strings (msgstr)
- [ ] Verificar sintaxe com `msgfmt --check`
- [ ] Testar no WordPress
- [ ] Compilar arquivo `.mo`
- [ ] Documentar no README
- [ ] Enviar Pull Request

## 🔍 Debug de Traduções

### Verificar se as Traduções Estão Carregando

```php
// Adicione este código temporariamente para debug
add_action('admin_notices', function() {
    $locale = get_locale();
    $textdomain = 'simple-locator-wc-filter';

    echo '<div class="notice notice-info">';
    echo '<p>Locale atual: ' . $locale . '</p>';
    echo '<p>Textdomain: ' . $textdomain . '</p>';
    echo '<p>Tradução carregada: ' . (is_textdomain_loaded($textdomain) ? 'Sim' : 'Não') . '</p>';
    echo '</div>';
});
```

### Verificar Arquivos de Tradução

```bash
# Listar arquivos .mo
ls -la languages/*.mo

# Verificar conteúdo de um arquivo .mo
msgunfmt languages/simple-locator-wc-filter-pt_BR.mo | head -20
```

## 🌐 Contribuindo com Traduções

### Como Contribuir

1. **Fork o repositório** no GitHub
2. **Crie um novo arquivo** `.po` baseado no template
3. **Traduza as strings** usando um editor apropriado
4. **Teste a tradução** no WordPress
5. **Compile o arquivo** `.mo`
6. **Envie um Pull Request**

### Padrões de Tradução

- **Mantenha consistência** com outras traduções
- **Use linguagem formal** quando apropriado
- **Preserve formatação** HTML e placeholders
- **Teste em contexto** real do plugin

## 📞 Suporte

Para dúvidas sobre traduções:

1. **Abra uma Issue** no GitHub
2. **Use a tag** `[translation]` no título
3. **Especifique** o idioma e problema
4. **Inclua exemplos** se possível

## 📄 Licença

As traduções seguem a mesma licença do plugin: **GPL v2 ou posterior**.

---

**Desenvolvido por:** David William da Costa  
**Versão:** 0.2.0  
**GitHub:** https://github.com/agenciadw/simple-locator-filter
