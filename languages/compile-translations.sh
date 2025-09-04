#!/bin/bash

# Script para compilar arquivos de tradução do Simple Locator WooCommerce Filter
# Autor: David William da Costa
# Versão: 0.1.0

echo "🔧 Compilando arquivos de tradução..."
echo "======================================"

# Verificar se o gettext está instalado
if ! command -v msgfmt &> /dev/null; then
    echo "❌ Erro: msgfmt não encontrado. Instale o gettext:"
    echo "   Ubuntu/Debian: sudo apt-get install gettext"
    echo "   macOS: brew install gettext"
    echo "   Windows: Baixe de https://mlocati.github.io/articles/gettext-iconv-windows.html"
    exit 1
fi

# Função para compilar um arquivo .po
compile_po() {
    local po_file="$1"
    local mo_file="${po_file%.po}.mo"
    
    if [ -f "$po_file" ]; then
        echo "📝 Compilando $po_file..."
        if msgfmt "$po_file" -o "$mo_file"; then
            echo "✅ $mo_file criado com sucesso!"
        else
            echo "❌ Erro ao compilar $po_file"
            return 1
        fi
    else
        echo "⚠️  Arquivo $po_file não encontrado"
    fi
}

# Compilar arquivo .po em português
compile_po "simple-locator-wc-filter-pt_BR.po"

echo ""
echo "🎉 Compilação concluída!"
echo ""
echo "📁 Arquivos gerados:"
ls -la *.mo 2>/dev/null || echo "Nenhum arquivo .mo encontrado"

echo ""
echo "📋 Próximos passos:"
echo "1. O arquivo .mo foi criado automaticamente"
echo "2. O WordPress detectará a tradução automaticamente"
echo "3. Configure o idioma português no WordPress"
echo ""
echo "🇧🇷 Tradução em português brasileiro ativa!"
