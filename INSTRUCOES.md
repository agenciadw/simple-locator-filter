# Instruções para o Filtro Simple Locator + WooCommerce

**Autor:** David William da Costa  
**Versão:** 0.1.0  
**Plugin URI:** https://github.com/agenciadw/simple-locator-filter

## ✅ Problemas Corrigidos

1. **Busca de coordenadas melhorada**: O código agora tenta múltiplos campos de latitude/longitude
2. **Debug controlado**: Adicionado parâmetro `debug="true"` para ativar informações de debug
3. **Validação de dados**: Melhor verificação se as coordenadas são válidas
4. **Tratamento de erros**: Melhor tratamento quando o Google Maps não carrega
5. **Layout otimizado**: Mapa aparece primeiro, lista de produtos com 3 colunas e design melhorado

## 🚀 Como Usar

### 1. Shortcode Básico

```
[simple_locator_filter credenciais="Depósito de Jardim"]
```

### 2. Shortcode com Debug (para identificar problemas)

```
[simple_locator_filter credenciais="Depósito de Jardim" debug="true"]
```

### 3. Shortcode Completo

```
[simple_locator_filter
    credenciais="Depósito de Jardim"
    category="jardim"
    map_height="500px"
    show_list="true"
    zoom="12"
    debug="true"]
```

## 🔧 Parâmetros Disponíveis

- `credenciais`: Valor do atributo WooCommerce "credenciais"
- `category`: Categoria de produto (slug)
- `product_ids`: IDs específicos de produtos (separados por vírgula)
- `map_height`: Altura do mapa (ex: "400px", "50vh")
- `show_list`: Mostrar lista de produtos ("true" ou "false")
- `zoom`: Zoom inicial do mapa (1-20)
- `debug`: Ativar debug ("true" ou "false")

## 🐛 Como Resolver Problemas

### 1. Produtos não aparecem no mapa

**Problema**: Os produtos são encontrados mas não aparecem no mapa.

**Solução**: Use o debug para verificar se as coordenadas estão sendo encontradas:

```
[simple_locator_filter credenciais="Depósito de Jardim" debug="true"]
```

### 2. Descobrir onde estão as coordenadas

Se o debug mostrar que as coordenadas estão vazias, use este shortcode para ver todos os meta campos de um produto:

```
[debug_product_meta product_id="123"]
```

Substitua "123" pelo ID do produto que você quer analisar.

### 3. Adicionar coordenadas aos produtos

O código já inclui campos personalizados para latitude, longitude e endereço. Eles aparecem no painel de administração do produto:

- **Latitude**: `_simple_locator_lat`
- **Longitude**: `_simple_locator_lng`
- **Endereço**: `_simple_locator_address`

### 4. Google Maps não carrega

**Verificar**:

1. Se a API key do Google Maps está configurada
2. Se o plugin Simple Locator está ativo
3. Se há erros no console do navegador

## 📋 Campos de Coordenadas Suportados

O código tenta buscar coordenadas nos seguintes campos (em ordem de prioridade):

**Latitude**:

- `_simple_locator_lat`
- `_wp_geo_latitude`
- `latitude`
- `lat`
- `wpmaps_lat`

**Longitude**:

- `_simple_locator_lng`
- `_wp_geo_longitude`
- `longitude`
- `lng`
- `wpmaps_lng`

**Endereço**:

- `_simple_locator_address`
- `_wp_geo_address`
- `address`
- `wpmaps_location`

## 🎯 Exemplos de Uso

### Exemplo 1: Filtro por credenciais

```
[simple_locator_filter credenciais="Depósito de Jardim"]
```

### Exemplo 2: Filtro por categoria

```
[simple_locator_filter category="jardim"]
```

### Exemplo 3: Produtos específicos

```
[simple_locator_filter product_ids="123,456,789"]
```

### Exemplo 4: Mapa grande sem lista

```
[simple_locator_filter
    credenciais="Depósito de Jardim"
    map_height="600px"
    show_list="false"
    zoom="15"]
```

### Exemplo 5: Layout otimizado (mapa primeiro + 3 colunas)

```
[simple_locator_filter
    credenciais="Depósito de Jardim"
    map_height="500px"
    show_list="true"
    zoom="12"]
```

## 🔍 Debug Avançado

Para analisar um produto específico e ver todos os seus meta campos:

1. Vá ao painel de administração do WordPress
2. Encontre o ID do produto
3. Use o shortcode: `[debug_product_meta product_id="ID_DO_PRODUTO"]`
4. Procure por campos que contenham coordenadas

## ⚠️ Requisitos

- WordPress com WooCommerce ativo
- Plugin Simple Locator ativo
- API key do Google Maps configurada
- Produtos com coordenadas de latitude e longitude

## 🆘 Suporte

Se ainda houver problemas:

1. Ative o debug: `debug="true"`
2. Verifique se os produtos têm coordenadas válidas
3. Confirme se a API key do Google Maps está funcionando
4. Verifique se o atributo "credenciais" existe e tem valores
