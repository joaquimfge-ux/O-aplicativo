/**
 * JavaScript da Aplicação CustomStorage
 * Tratamento de cálculos em tempo real, interações e validações de interface
 */

document.addEventListener('DOMContentLoaded', function () {
    // 1. Preenchimento Rápido na tela de Login (Atalhos de Teste)
    const quickButtons = document.querySelectorAll('.quick-fill');
    quickButtons.forEach(btn => {
        btn.addEventListener('click', function () {
            const emailInput = document.getElementById('email');
            const senhaInput = document.getElementById('senha');
            if (emailInput && senhaInput) {
                emailInput.value = this.dataset.email || '';
                senhaInput.value = this.dataset.senha || '';
            }
        });
    });

    // 2. Formulário de Montagem de Pedido e Cálculo Dinâmico
    const orderForm = document.getElementById('orderForm');
    if (orderForm) {
        const basePriceInput = document.getElementById('base_price');
        const basePrice = basePriceInput ? parseFloat(basePriceInput.value) : 0;

        const capacityRadios = document.querySelectorAll('.capacity-radio');
        const tipoPronto = document.getElementById('tipo_pronto');
        const tipoPersonalizado = document.getElementById('tipo_personalizado');
        const secaoPersonalizacao = document.getElementById('secao_personalizacao');

        const resumoCapLabel = document.getElementById('resumo_cap_label');
        const resumoCapPreco = document.getElementById('resumo_cap_preco');
        const resumoPersLinha = document.getElementById('resumo_pers_linha');
        const resumoTotal = document.getElementById('resumo_total');

        function formatBRL(val) {
            return 'R$ ' + val.toFixed(2).replace('.', ',').replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        }

        function recalcularTotal() {
            let capAdicional = 0;
            let capLabel = '32 GB';

            // Capacidade selecionada
            capacityRadios.forEach(radio => {
                const parentCard = radio.closest('.capacity-option');
                if (radio.checked) {
                    capAdicional = parseFloat(radio.dataset.additional || 0);
                    capLabel = radio.dataset.label || '';
                    if (parentCard) {
                        parentCard.classList.add('border-primary', 'bg-primary-subtle');
                    }
                } else if (parentCard) {
                    parentCard.classList.remove('border-primary', 'bg-primary-subtle');
                }
            });

            // Personalização
            const isPers = tipoPersonalizado && tipoPersonalizado.checked;
            const taxaPers = isPers ? 25.00 : 0.00;

            if (tipoPronto && tipoPersonalizado) {
                const cardPronto = tipoPronto.closest('.order-type-card');
                const cardPers = tipoPersonalizado.closest('.order-type-card');

                if (isPers) {
                    if (secaoPersonalizacao) secaoPersonalizacao.classList.remove('d-none');
                    if (resumoPersLinha) resumoPersLinha.style.setProperty('display', 'flex', 'important');
                    if (cardPers) cardPers.classList.add('border-primary', 'bg-primary-subtle');
                    if (cardPronto) cardPronto.classList.remove('border-primary', 'bg-primary-subtle');
                } else {
                    if (secaoPersonalizacao) secaoPersonalizacao.classList.add('d-none');
                    if (resumoPersLinha) resumoPersLinha.style.setProperty('display', 'none', 'important');
                    if (cardPronto) cardPronto.classList.add('border-primary', 'bg-primary-subtle');
                    if (cardPers) cardPers.classList.remove('border-primary', 'bg-primary-subtle');
                }
            }

            // Atualiza labels no resumo lateral
            if (resumoCapLabel) resumoCapLabel.textContent = capLabel;
            if (resumoCapPreco) {
                resumoCapPreco.textContent = (capAdicional > 0) ? '+ ' + formatBRL(capAdicional) : '+ R$ 0,00';
            }

            const total = basePrice + capAdicional + taxaPers;
            if (resumoTotal) {
                resumoTotal.textContent = formatBRL(total);
            }
        }

        // Listeners para mudanças
        capacityRadios.forEach(r => r.addEventListener('change', recalcularTotal));
        if (tipoPronto) tipoPronto.addEventListener('change', recalcularTotal);
        if (tipoPersonalizado) tipoPersonalizado.addEventListener('change', recalcularTotal);

        // Execução inicial
        recalcularTotal();
    }
});
