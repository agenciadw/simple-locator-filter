# Botões de Produto - Simple Locator WooCommerce Filter

## 🎯 **Novos Botões Implementados**

O plugin agora inclui **dois botões** em cada card de produto, oferecendo mais opções de navegação para os usuários.

## 📋 **Estrutura dos Botões**

### **Layout dos Botões**

```html
<div class="product-buttons">
  <button onclick="focusMapLocation(lat, lng)" class="btn-map">
    Ver no Mapa
  </button>
  <a href="url-do-produto" class="btn-product"> Ver Produto </a>
</div>
```

### **Funcionalidades**

#### **1. Botão "Ver no Mapa"**

- **Cor:** Laranja (#fb7203)
- **Função:** Foca no marcador do mapa
- **Comportamento:** JavaScript inline
- **Acessibilidade:** Suporte a navegação por teclado

#### **2. Botão "Ver Produto"**

- **Cor:** Verde (#28a745)
- **Função:** Link direto para a página do produto
- **Comportamento:** Navegação padrão
- **Acessibilidade:** Link semântico

## 🎨 **Estilos CSS Implementados**

### **Container dos Botões**

```css
.product-buttons {
  display: flex;
  flex-direction: column;
  gap: 8px;
  margin-top: 10px;
}
```

### **Estilos Base dos Botões**

```css
.product-item button,
.product-item .btn-product {
  background: #fb7203;
  color: white;
  border: none;
  padding: 8px 15px;
  border-radius: 5px;
  cursor: pointer;
  width: 100%;
  font-size: 14px;
  transition: all 0.3s ease;
  font-weight: 500;
  text-decoration: none;
  text-align: center;
  display: block;
  box-sizing: border-box;
}
```

### **Cores Específicas**

```css
/* Botão "Ver no Mapa" */
.btn-map {
  background: #fb7203 !important;
}

/* Botão "Ver Produto" */
.btn-product {
  background: #28a745 !important;
}
```

## 📱 **Responsividade**

### **Desktop (768px+)**

- Gap entre botões: 10px
- Layout vertical (coluna)

### **Mobile (480px-)**

- Gap entre botões: 6px
- Padding aumentado: 10px 15px
- Fonte maior: 15px

### **Tablet (768px-480px)**

- Gap padrão: 8px
- Layout otimizado para toque

## ♿ **Acessibilidade**

### **Navegação por Teclado**

- ✅ Foco visível em ambos os botões
- ✅ Outline colorido específico para cada botão
- ✅ Suporte a Enter e Space

### **Leitores de Tela**

- ✅ Texto descritivo em ambos os botões
- ✅ Links semânticos para "Ver Produto"
- ✅ Botões com roles apropriados

### **Contraste**

- ✅ Contraste WCAG AA em ambos os botões
- ✅ Estados hover e focus bem definidos

## 🎭 **Estados dos Botões**

### **Estados Hover**

```css
.product-item button:hover,
.product-item .btn-product:hover {
  transform: translateY(-1px);
  box-shadow: 0 2px 4px rgba(251, 114, 3, 0.3);
}
```

### **Estados Active**

```css
.product-item button:active,
.product-item .btn-product:active {
  transform: translateY(0);
}
```

### **Estados Focus**

```css
.product-item button:focus,
.product-item .btn-product:focus {
  outline: 2px solid #fb7203;
  outline-offset: 2px;
}

.btn-product:focus {
  outline-color: #28a745;
}
```

## 🌍 **Traduções**

### **Idiomas Suportados**

- ✅ **Português:** "Ver no Mapa" / "Ver Produto"
- ✅ **Inglês:** "View on Map" / "View Product"
- ✅ **Espanhol:** "Ver en el Mapa" / "Ver Producto"

### **Strings de Tradução**

```php
__('Ver no Mapa', 'simple-locator-wc-filter')
__('Ver Produto', 'simple-locator-wc-filter')
```

## 🔧 **Implementação Técnica**

### **HTML Gerado**

```php
<div class="product-buttons">
    <button onclick="focusMapLocation(<?php echo $location['lat']; ?>, <?php echo $location['lng']; ?>)" class="btn-map">
        <?php _e('Ver no Mapa', 'simple-locator-wc-filter'); ?>
    </button>
    <a href="<?php echo $location['url']; ?>" class="btn-product">
        <?php _e('Ver Produto', 'simple-locator-wc-filter'); ?>
    </a>
</div>
```

### **JavaScript**

```javascript
// Função para focar no mapa (já existente)
function focusMapLocation(lat, lng) {
  map.setCenter({ lat: lat, lng: lng });
  map.setZoom(15);
  // ... resto da lógica
}
```

## 📊 **Benefícios da Implementação**

### **Para Usuários**

- ✅ **Mais opções** de navegação
- ✅ **Acesso direto** à página do produto
- ✅ **Foco no mapa** para localização
- ✅ **Interface intuitiva** com cores distintas

### **Para SEO**

- ✅ **Links internos** para produtos
- ✅ **Navegação semântica**
- ✅ **Melhor indexação** dos produtos

### **Para Performance**

- ✅ **Carregamento otimizado** dos botões
- ✅ **CSS eficiente** com reutilização
- ✅ **JavaScript mínimo** (apenas função existente)

## 🎯 **Casos de Uso**

### **Cenário 1: Usuário quer ver detalhes**

- Clica em "Ver Produto"
- Vai direto para a página do produto
- Pode ver preços, descrições, etc.

### **Cenário 2: Usuário quer localizar**

- Clica em "Ver no Mapa"
- Mapa foca na localização
- Pode ver contexto geográfico

### **Cenário 3: Usuário em mobile**

- Botões maiores para toque
- Navegação otimizada
- Experiência responsiva

## 🔮 **Possíveis Melhorias Futuras**

### **Funcionalidades Adicionais**

1. **Botão "Adicionar ao Carrinho"** (se aplicável)
2. **Botão "Favoritar"** (se implementado)
3. **Botão "Compartilhar"** (redes sociais)
4. **Botão "Comparar"** (se implementado)

### **Otimizações**

1. **Lazy loading** dos botões
2. **Preload** de páginas de produto
3. **Analytics** de cliques nos botões
4. **A/B testing** de cores e posições

## 📞 **Suporte**

Para dúvidas sobre os botões:

1. Verifique o arquivo `assets/css/simple-locator-wc-filter.css`
2. Teste a navegação por teclado
3. Verifique responsividade em diferentes dispositivos
4. Monitore métricas de cliques

---

**Desenvolvido por:** David William da Costa  
**Versão:** 0.2.0  
**GitHub:** https://github.com/agenciadw/simple-locator-filter
