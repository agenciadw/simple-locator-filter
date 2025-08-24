# Guia de Instalação - Simple Locator WooCommerce Filter

**Autor:** David William da Costa  
**Versão:** 0.1.0  
**Plugin URI:** https://github.com/agenciadw/simple-locator-filter

## 📦 Arquivos do Plugin

O plugin consiste em um único arquivo:

- `simple-locator-woocommerce-filter.php` - Arquivo principal do plugin

## 🚀 Instalação Passo a Passo

### Passo 1: Preparação

1. **Verifique os requisitos:**

   - WordPress 5.0+
   - WooCommerce 5.0+
   - Plugin Simple Locator ativo
   - PHP 7.4+

2. **Obtenha uma API Key do Google Maps:**
   - Acesse [Google Cloud Console](https://console.cloud.google.com/)
   - Crie um novo projeto ou selecione um existente
   - Ative a **Maps JavaScript API**
   - Crie uma chave de API
   - Anote a chave (você precisará dela depois)

### Passo 2: Upload do Plugin

#### Opção A: Via Painel Administrativo (Recomendado)

1. Acesse o painel administrativo do WordPress
2. Vá em **Plugins > Adicionar Novo**
3. Clique em **Enviar Plugin**
4. Selecione o arquivo `simple-locator-woocommerce-filter.php`
5. Clique em **Instalar Agora**
6. Após a instalação, clique em **Ativar Plugin**

#### Opção B: Via FTP

1. Conecte-se ao seu servidor via FTP
2. Navegue até `/wp-content/plugins/`
3. Crie uma pasta chamada `simple-locator-woocommerce-filter`
4. Faça upload do arquivo `simple-locator-woocommerce-filter.php` para esta pasta
5. Vá ao painel administrativo e ative o plugin

### Passo 3: Configuração

1. **Configure a API Key do Google Maps:**

   - Vá em **Configurações > SL WC Filter**
   - Cole sua API Key do Google Maps no campo correspondente
   - Clique em **Salvar Alterações**

2. **Verifique se tudo está funcionando:**
   - O plugin deve aparecer na lista de plugins ativos
   - Não deve haver mensagens de erro no painel

### Passo 4: Configuração dos Produtos

1. **Adicione coordenadas aos produtos:**

   - Vá em **Produtos > Editar**
   - Role até a seção "Dados do produto"
   - Preencha os campos:
     - **Latitude**: Ex: -23.5505
     - **Longitude**: Ex: -46.6333
     - **Endereço**: Endereço completo

2. **Configure o atributo "credenciais":**
   - Vá em **Produtos > Atributos**
   - Crie ou edite o atributo "credenciais"
   - Adicione valores como "Depósito de Jardim", "Loja Central", etc.

### Passo 5: Teste

1. **Crie uma página de teste:**

   - Vá em **Páginas > Adicionar Nova**
   - Adicione o shortcode: `[simple_locator_filter credenciais="Depósito de Jardim"]`
   - Publique a página

2. **Teste com debug:**
   - Use: `[simple_locator_filter credenciais="Depósito de Jardim" debug="true"]`
   - Isso mostrará informações detalhadas sobre o funcionamento

## 🔧 Configurações Avançadas

### Personalizar o Mapa

```php
[simple_locator_filter
    credenciais="Depósito de Jardim"
    map_height="600px"
    zoom="15"
    show_list="true"]
```

### Filtrar por Categoria

```php
[simple_locator_filter
    category="jardim"
    map_height="500px"]
```

### Produtos Específicos

```php
[simple_locator_filter
    product_ids="123,456,789"
    map_height="400px"]
```

## 🐛 Solução de Problemas

### Problema: Plugin não aparece na lista

**Solução:**

- Verifique se o arquivo está na pasta correta
- Verifique se o cabeçalho do plugin está correto
- Verifique se não há erros de sintaxe

### Problema: "Plugin requer WooCommerce"

**Solução:**

- Instale e ative o WooCommerce
- Verifique se está na versão 5.0+

### Problema: "Plugin requer Simple Locator"

**Solução:**

- Instale e ative o plugin Simple Locator
- Verifique se está funcionando corretamente

### Problema: Mapa não carrega

**Solução:**

- Verifique se a API Key está configurada
- Verifique se a Maps JavaScript API está ativa
- Verifique o console do navegador para erros

### Problema: Produtos não aparecem

**Solução:**

- Use o debug: `debug="true"`
- Verifique se os produtos têm coordenadas
- Verifique se o filtro está correto

## 📞 Suporte

Se você encontrar problemas:

1. **Ative o debug** para ver informações detalhadas
2. **Verifique o console** do navegador para erros JavaScript
3. **Verifique os logs** do WordPress para erros PHP
4. **Teste com produtos simples** primeiro

## ✅ Checklist de Instalação

- [ ] WordPress 5.0+ instalado
- [ ] WooCommerce 5.0+ instalado e ativo
- [ ] Simple Locator instalado e ativo
- [ ] Plugin carregado via painel ou FTP
- [ ] Plugin ativado
- [ ] API Key do Google Maps configurada
- [ ] Produtos com coordenadas configuradas
- [ ] Atributo "credenciais" configurado
- [ ] Shortcode testado com sucesso
- [ ] Debug funcionando (opcional)

## 🎯 Próximos Passos

Após a instalação bem-sucedida:

1. **Personalize o design** com CSS customizado
2. **Configure filtros específicos** para seu negócio
3. **Otimize as coordenadas** dos produtos
4. **Teste em diferentes dispositivos** para responsividade
5. **Configure SEO** para as páginas com mapa
