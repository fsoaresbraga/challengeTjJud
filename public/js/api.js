window.ApiClient = (function () {
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

    async function request(url, options = {}) {
        const response = await fetch(url, {
            headers: {
                Accept: 'application/json',
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                ...(csrfToken ? { 'X-CSRF-TOKEN': csrfToken } : {}),
                ...(options.headers || {}),
            },
            ...options,
        });

        if (response.status === 204) {
            return null;
        }

        const payload = await response.json().catch(() => ({}));

        if (!response.ok) {
            const error = new Error(payload.message || 'Request failed');
            error.status = response.status;
            error.payload = payload;
            throw error;
        }

        return payload;
    }

    async function fetchPage(endpoint, page = 1, perPage = 50) {
        const separator = endpoint.includes('?') ? '&' : '?';
        return request(`${endpoint}${separator}page=${page}&perPage=${perPage}`);
    }

    /**
     * Loads paginated resources in batches until the last page.
     * Avoids unbounded all() queries while still filling selects/lists.
     */
    async function fetchAllPages(endpoint, perPage = 50, onBatch = null) {
        const items = [];
        let page = 1;
        let lastPage = 1;

        do {
            const payload = await fetchPage(endpoint, page, perPage);
            const batch = payload.data || [];
            items.push(...batch);

            if (typeof onBatch === 'function') {
                onBatch(batch, page, payload.meta || {});
            }

            lastPage = payload.meta?.last_page || 1;
            page += 1;
        } while (page <= lastPage);

        return items;
    }

    function formatCurrencyBRL(value) {
        const number = Number(value || 0);
        return number.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });
    }

    function parseCurrencyBRL(masked) {
        if (typeof masked === 'number') {
            return masked;
        }

        const normalized = String(masked || '')
            .replace(/[^\d,.-]/g, '')
            .replace(/\./g, '')
            .replace(',', '.');

        return Number.parseFloat(normalized || '0');
    }

    function validationMessage(error) {
        if (error?.payload?.errors) {
            return Object.values(error.payload.errors).flat().join(' ');
        }

        return error?.payload?.message || error?.message || 'Unexpected error';
    }

    return {
        request,
        fetchPage,
        fetchAllPages,
        formatCurrencyBRL,
        parseCurrencyBRL,
        validationMessage,
        get: (url) => request(url),
        post: (url, body) => request(url, { method: 'POST', body: JSON.stringify(body) }),
        put: (url, body) => request(url, { method: 'PUT', body: JSON.stringify(body) }),
        destroy: (url) => request(url, { method: 'DELETE' }),
    };
})();
