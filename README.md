# Simple Locator WooCommerce Filter

Um plugin WordPress que integra o Simple Locator com produtos do WooCommerce, permitindo filtrar produtos por localização e exibi-los em um mapa interativo.

**Autor:** David William da Costa  
**Versão:** 0.1.0  
**Plugin URI:** https://github.com/agenciadw/simple-locator-filter

## 🚀 Características

- ✅ **Integração completa** com WooCommerce e Simple Locator
- ✅ **Filtro por atributos** de produtos (ex: credenciais)
- ✅ **Filtro por categorias** de produtos
- ✅ **Mapa interativo** com Google Maps
- ✅ **Layout responsivo** (3 colunas no desktop, 2 no tablet, 1 no mobile)
- ✅ **Campos personalizados** para latitude, longitude e endereço
- ✅ **Debug integrado** para identificar problemas
- ✅ **Painel de administração** para configurações
- ✅ **Traduzível** (i18n)

## 📋 Requisitos

- WordPress 5.0 ou superior
- WooCommerce 5.0 ou superior
- Plugin Simple Locator ativo
- API Key do Google Maps
- PHP 7.4 ou superior

## 🔧 Instalação

### 1. Upload do Plugin

1. Faça upload do arquivo `simple-locator-woocommerce-filter.php` para a pasta `/wp-content/plugins/`
2. Ative o plugin através do menu 'Plugins' no WordPress
3. Configure a API Key do Google Maps em **Configurações > SL WC Filter**

### 2. Configuração da API Key do Google Maps

1. Acesse [Google Cloud Console](https://console.cloud.google.com/)
2. Crie um novo projeto ou selecione um existente
3. Ative a Maps JavaScript API
4. Crie uma chave de API
5. Cole a chave em **Configurações > SL WC Filter**

## 🎯 Como Usar

### Shortcode Básico

```
[simple_locator_filter credenciais="Depósito de Jardim"]
```

### Shortcode com Debug

```
[simple_locator_filter credenciais="Depósito de Jardim" debug="true"]
```

### Shortcode Completo

```
[simple_locator_filter
    credenciais="Depósito de Jardim"
    category="jardim"
    map_height="500px"
    show_list="true"
    zoom="12"]
```

## 📋 Parâmetros Disponíveis

| Parâmetro     | Descrição                                           | Padrão  |
| ------------- | --------------------------------------------------- | ------- |
| `credenciais` | Valor do atributo WooCommerce "credenciais"         | vazio   |
| `category`    | Categoria de produto (slug)                         | vazio   |
| `product_ids` | IDs específicos de produtos (separados por vírgula) | vazio   |
| `map_height`  | Altura do mapa (ex: "400px", "50vh")                | "400px" |
| `show_list`   | Mostrar lista de produtos ("true" ou "false")       | "true"  |
| `zoom`        | Zoom inicial do mapa (1-20)                         | "10"    |
| `debug`       | Ativar debug ("true" ou "false")                    | "false" |

## 🛠️ Configuração dos Produtos

### Adicionar Coordenadas aos Produtos

1. Vá ao painel de administração do WordPress
2. Edite um produto
3. Role até a seção "Dados do produto"
4. Preencha os campos:
   - **Latitude**: Ex: -23.5505
   - **Longitude**: Ex: -46.6333
   - **Endereço**: Endereço completo

### Campos Suportados

O plugin busca coordenadas nos seguintes campos (em ordem de prioridade):

**Latitude:**

- `_simple_locator_lat`
- `_wp_geo_latitude`
- `latitude`
- `lat`
- `wpmaps_lat`

**Longitude:**

- `_simple_locator_lng`
- `_wp_geo_longitude`
- `longitude`
- `lng`
- `wpmaps_lng`

**Endereço:**

- `_simple_locator_address`
- `_wp_geo_address`
- `address`
- `wpmaps_location`

## 🐛 Debug e Solução de Problemas

### 1. Produtos não aparecem no mapa

Use o debug para verificar se as coordenadas estão sendo encontradas:

```
[simple_locator_filter credenciais="Depósito de Jardim" debug="true"]
```

### 2. Descobrir onde estão as coordenadas

Use este shortcode para ver todos os meta campos de um produto:

```
[debug_product_meta product_id="123"]
```

### 3. Google Maps não carrega

Verifique:

1. Se a API key está configurada corretamente
2. Se a Maps JavaScript API está ativa no Google Cloud Console
3. Se há erros no console do navegador

## 🎨 Personalização

### CSS Customizado

Adicione CSS personalizado ao seu tema para modificar a aparência:

```css
/* Personalizar cards de produtos */
.product-item {
  background: #f9f9f9;
  border-radius: 10px;
}

/* Personalizar botões */
.product-item button {
  background: #fb7203 !important;
}
```

### JavaScript Customizado

O plugin expõe a função `focusMapLocation(lat, lng)` que pode ser chamada de JavaScript externo.

## 📱 Responsividade

O layout se adapta automaticamente:

- **Desktop**: 3 colunas
- **Tablet (≤768px)**: 2 colunas
- **Mobile (≤480px)**: 1 coluna

## 🔒 Segurança

- Todas as entradas são sanitizadas
- Verificação de permissões de administrador
- Prevenção de acesso direto aos arquivos
- Validação de dados de entrada

## 📄 Licença

Este plugin é licenciado sob GPL v2 ou posterior.

## 🤝 Suporte

Para suporte e dúvidas:

1. Verifique se todos os requisitos estão atendidos
2. Ative o debug para identificar problemas
3. Verifique se a API key do Google Maps está funcionando
4. Confirme se os produtos têm coordenadas válidas

## 🔄 Changelog

### Versão 0.1.0

- Lançamento inicial
- Integração com WooCommerce e Simple Locator
- Mapa interativo com Google Maps
- Layout responsivo
- Sistema de debug
- Painel de administração
- Campos personalizados para localização
