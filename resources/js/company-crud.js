function escapeHtml(value) {
    return String(value ?? '')
        .replaceAll('&', '&amp;')
        .replaceAll('<', '&lt;')
        .replaceAll('>', '&gt;')
        .replaceAll('"', '&quot;')
        .replaceAll("'", '&#39;');
}

function getModulesConfig() {
    return {
        customers: {
            title: 'Customers',
            endpoint: '/api/v1/customers',
            listColumns: ['id', 'full_name', 'company_name', 'email', 'phone', 'is_active'],
            createFields: [
                {
                    key: 'branch_id',
                    label: 'Branch',
                    type: 'api-select',
                    required: true,
                    source: '/api/v1/branches',
                    labelKeys: ['name', 'code'],
                },
                {
                    key: 'customer_type',
                    label: 'Customer Type',
                    type: 'select',
                    required: true,
                    options: [
                        { value: 'person', label: 'Person' },
                        { value: 'company', label: 'Company' },
                    ],
                },
                { key: 'first_name', label: 'First Name', type: 'text' },
                { key: 'last_name', label: 'Last Name', type: 'text' },
                { key: 'company_name', label: 'Company Name', type: 'text' },
                { key: 'document_type', label: 'Document Type', type: 'text' },
                { key: 'document_number', label: 'Document Number', type: 'text' },
                { key: 'email', label: 'Email', type: 'email' },
                { key: 'phone', label: 'Phone', type: 'text' },
                { key: 'mobile', label: 'Mobile', type: 'text' },
                { key: 'address', label: 'Address', type: 'text' },
                { key: 'city', label: 'City', type: 'text' },
                { key: 'state', label: 'State', type: 'text' },
                { key: 'country', label: 'Country', type: 'text' },
                { key: 'credit_limit', label: 'Credit Limit', type: 'number', step: '0.01' },
                { key: 'notes', label: 'Notes', type: 'textarea' },
                { key: 'accepts_marketing', label: 'Accepts Marketing', type: 'checkbox' },
                { key: 'is_active', label: 'Is Active', type: 'checkbox' },
            ],
            editFields: [
                {
                    key: 'customer_type',
                    label: 'Customer Type',
                    type: 'select',
                    options: [
                        { value: 'person', label: 'Person' },
                        { value: 'company', label: 'Company' },
                    ],
                },
                { key: 'first_name', label: 'First Name', type: 'text' },
                { key: 'last_name', label: 'Last Name', type: 'text' },
                { key: 'company_name', label: 'Company Name', type: 'text' },
                { key: 'document_type', label: 'Document Type', type: 'text' },
                { key: 'document_number', label: 'Document Number', type: 'text' },
                { key: 'email', label: 'Email', type: 'email' },
                { key: 'phone', label: 'Phone', type: 'text' },
                { key: 'mobile', label: 'Mobile', type: 'text' },
                { key: 'address', label: 'Address', type: 'text' },
                { key: 'city', label: 'City', type: 'text' },
                { key: 'state', label: 'State', type: 'text' },
                { key: 'country', label: 'Country', type: 'text' },
                { key: 'credit_limit', label: 'Credit Limit', type: 'number', step: '0.01' },
                { key: 'notes', label: 'Notes', type: 'textarea' },
                { key: 'accepts_marketing', label: 'Accepts Marketing', type: 'checkbox' },
                { key: 'is_active', label: 'Is Active', type: 'checkbox' },
            ],
        },
        tickets: {
            title: 'Tickets',
            endpoint: '/api/v1/tickets',
            listColumns: ['id', 'ticket_number', 'status', 'priority', 'customer_id', 'device_id'],
            createFields: [
                {
                    key: 'company_id',
                    label: 'Company',
                    type: 'api-select',
                    required: true,
                    source: '/api/v1/companies',
                    labelKeys: ['name', 'slug'],
                },
                {
                    key: 'branch_id',
                    label: 'Branch',
                    type: 'api-select',
                    required: true,
                    source: '/api/v1/branches',
                    labelKeys: ['name', 'code'],
                },
                {
                    key: 'customer_id',
                    label: 'Customer',
                    type: 'api-select',
                    required: true,
                    source: '/api/v1/customers',
                    labelKeys: ['full_name', 'company_name', 'email'],
                },
                {
                    key: 'device_id',
                    label: 'Device',
                    type: 'api-select',
                    required: true,
                    source: '/api/v1/devices',
                    labelKeys: ['brand', 'model', 'serial_number'],
                },
                {
                    key: 'technician_id',
                    label: 'Technician',
                    type: 'api-select',
                    source: '/api/v1/users',
                    labelKeys: ['name', 'email'],
                },
                {
                    key: 'priority',
                    label: 'Priority',
                    type: 'select',
                    options: ['LOW', 'MEDIUM', 'HIGH', 'URGENT']
                        .map((value) => ({ value, label: value })),
                },
                {
                    key: 'channel',
                    label: 'Channel',
                    type: 'select',
                    options: ['COUNTER', 'PHONE', 'WHATSAPP', 'EMAIL', 'WEB']
                        .map((value) => ({ value, label: value })),
                },
                { key: 'reported_problem', label: 'Reported Problem', type: 'textarea', required: true },
                { key: 'customer_notes', label: 'Customer Notes', type: 'textarea' },
                { key: 'is_warranty', label: 'Is Warranty', type: 'checkbox' },
            ],
            editFields: [
                {
                    key: 'technician_id',
                    label: 'Technician',
                    type: 'api-select',
                    source: '/api/v1/users',
                    labelKeys: ['name', 'email'],
                },
                {
                    key: 'status',
                    label: 'Status',
                    type: 'select',
                    options: ['OPEN', 'ASSIGNED', 'IN_DIAGNOSIS', 'WAITING_QUOTE', 'APPROVED', 'IN_REPAIR', 'READY_DELIVERY', 'DELIVERED', 'CLOSED', 'CANCELLED']
                        .map((value) => ({ value, label: value })),
                },
                {
                    key: 'priority',
                    label: 'Priority',
                    type: 'select',
                    options: ['LOW', 'MEDIUM', 'HIGH', 'URGENT'].map((value) => ({ value, label: value })),
                },
                {
                    key: 'channel',
                    label: 'Channel',
                    type: 'select',
                    options: ['COUNTER', 'PHONE', 'WHATSAPP', 'EMAIL', 'WEB'].map((value) => ({ value, label: value })),
                },
                { key: 'reported_problem', label: 'Reported Problem', type: 'textarea' },
                { key: 'customer_notes', label: 'Customer Notes', type: 'textarea' },
                { key: 'is_warranty', label: 'Is Warranty', type: 'checkbox' },
            ],
        },
        invoices: {
            title: 'Invoices',
            endpoint: '/api/v1/invoices',
            listColumns: ['id', 'invoice_number', 'status', 'customer_id', 'total', 'currency'],
            createFields: [
                {
                    key: 'company_id',
                    label: 'Company',
                    type: 'api-select',
                    required: true,
                    source: '/api/v1/companies',
                    labelKeys: ['name', 'slug'],
                },
                {
                    key: 'branch_id',
                    label: 'Branch',
                    type: 'api-select',
                    required: true,
                    source: '/api/v1/branches',
                    labelKeys: ['name', 'code'],
                },
                {
                    key: 'customer_id',
                    label: 'Customer',
                    type: 'api-select',
                    required: true,
                    source: '/api/v1/customers',
                    labelKeys: ['full_name', 'company_name', 'email'],
                },
                {
                    key: 'billing_id',
                    label: 'Billing',
                    type: 'api-select',
                    source: '/api/v1/billings',
                    labelKeys: ['reference', 'status', 'amount'],
                },
                {
                    key: 'ticket_id',
                    label: 'Ticket',
                    type: 'api-select',
                    source: '/api/v1/tickets',
                    labelKeys: ['ticket_number', 'status'],
                },
                {
                    key: 'repair_id',
                    label: 'Repair',
                    type: 'api-select',
                    source: '/api/v1/repairs',
                    labelKeys: ['repair_number', 'status'],
                },
                { key: 'subtotal', label: 'Subtotal', type: 'number', step: '0.01' },
                { key: 'tax', label: 'Tax', type: 'number', step: '0.01' },
                { key: 'discount', label: 'Discount', type: 'number', step: '0.01' },
                { key: 'currency', label: 'Currency', type: 'text' },
                { key: 'exchange_rate', label: 'Exchange Rate', type: 'number', step: '0.0001' },
                { key: 'due_date', label: 'Due Date', type: 'date' },
                { key: 'notes', label: 'Notes', type: 'textarea' },
            ],
            editFields: [
                {
                    key: 'status',
                    label: 'Status',
                    type: 'select',
                    options: ['draft', 'issued', 'partially_paid', 'paid', 'overdue', 'cancelled', 'refunded']
                        .map((value) => ({ value, label: value })),
                },
                {
                    key: 'billing_id',
                    label: 'Billing',
                    type: 'api-select',
                    source: '/api/v1/billings',
                    labelKeys: ['reference', 'status', 'amount'],
                },
                { key: 'subtotal', label: 'Subtotal', type: 'number', step: '0.01' },
                { key: 'tax', label: 'Tax', type: 'number', step: '0.01' },
                { key: 'discount', label: 'Discount', type: 'number', step: '0.01' },
                { key: 'currency', label: 'Currency', type: 'text' },
                { key: 'exchange_rate', label: 'Exchange Rate', type: 'number', step: '0.0001' },
                { key: 'due_date', label: 'Due Date', type: 'date' },
                { key: 'notes', label: 'Notes', type: 'textarea' },
            ],
        },
    };
}

function normalizePayload(payload) {
    if (Array.isArray(payload)) {
        return payload;
    }
    if (Array.isArray(payload?.data)) {
        return payload.data;
    }
    if (payload?.data && typeof payload.data === 'object') {
        return payload.data;
    }
    return payload;
}

function buildHeaders() {
    const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ?? '';
    return {
        Accept: 'application/json',
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': token,
    };
}

async function apiRequest(url, method = 'GET', body = null) {
    const response = await fetch(url, {
        method,
        credentials: 'same-origin',
        headers: buildHeaders(),
        body: body ? JSON.stringify(body) : null,
    });

    const payload = await response.json().catch(() => ({}));

    if (!response.ok) {
        const error = new Error(payload.message || `HTTP ${response.status}`);
        error.payload = payload;
        error.status = response.status;
        throw error;
    }

    return payload;
}

function renderField(field, value) {
    const fieldId = `field-${field.key}`;
    const safeValue = value ?? '';

    if (field.type === 'textarea') {
        return `<label class="crud-label" for="${fieldId}">${field.label}</label>
            <textarea class="crud-input" id="${fieldId}" name="${field.key}" ${field.required ? 'required' : ''}>${escapeHtml(safeValue)}</textarea>`;
    }

    if (field.type === 'select') {
        const options = (field.options || [])
            .map((option) => `<option value="${escapeHtml(option.value)}" ${String(option.value) === String(safeValue) ? 'selected' : ''}>${escapeHtml(option.label)}</option>`)
            .join('');
        return `<label class="crud-label" for="${fieldId}">${field.label}</label>
            <select class="crud-input" id="${fieldId}" name="${field.key}" ${field.required ? 'required' : ''}>
                <option value="">Seleccione...</option>
                ${options}
            </select>`;
    }

    if (field.type === 'api-select') {
        return `<label class="crud-label" for="${fieldId}">${field.label}</label>
            <select class="crud-input" id="${fieldId}" name="${field.key}" data-api-select="${field.key}" ${field.required ? 'required' : ''}>
                <option value="">Cargando opciones...</option>
            </select>`;
    }

    if (field.type === 'checkbox') {
        return `<label class="crud-checkbox">
            <input type="checkbox" name="${field.key}" ${safeValue ? 'checked' : ''} />
            <span>${field.label}</span>
        </label>`;
    }

    return `<label class="crud-label" for="${fieldId}">${field.label}</label>
        <input class="crud-input" id="${fieldId}" name="${field.key}" type="${field.type}" value="${escapeHtml(safeValue)}" ${field.step ? `step="${field.step}"` : ''} ${field.required ? 'required' : ''} />`;
}

function buildOptionLabel(item, labelKeys) {
    const parts = (labelKeys || [])
        .map((key) => item?.[key])
        .filter((part) => part !== null && part !== undefined && part !== '');

    if (parts.length > 0) {
        return parts.join(' | ');
    }

    return `ID ${item?.id ?? '-'}`;
}

async function hydrateApiSelects(form, fields, data, setFeedback) {
    const apiSelectFields = fields.filter((field) => field.type === 'api-select');

    for (const field of apiSelectFields) {
        const input = form.elements.namedItem(field.key);
        if (!input) {
            continue;
        }

        try {
            const payload = await apiRequest(field.source);
            const options = normalizePayload(payload) || [];
            const currentValue = data?.[field.key] ?? '';
            const htmlOptions = options
                .map((item) => {
                    const value = item?.id;
                    const label = buildOptionLabel(item, field.labelKeys);
                    const selected = String(value) === String(currentValue) ? 'selected' : '';
                    return `<option value="${escapeHtml(value)}" ${selected}>${escapeHtml(label)}</option>`;
                })
                .join('');

            input.innerHTML = `<option value="">Seleccione...</option>${htmlOptions}`;
        } catch (error) {
            input.innerHTML = '<option value="">No disponible</option>';
            setFeedback(`No fue posible cargar opciones de ${field.label}.`, true);
        }
    }
}

function collectFormData(form, fields) {
    const payload = {};

    fields.forEach((field) => {
        const input = form.elements.namedItem(field.key);
        if (!input) {
            return;
        }

        if (field.type === 'checkbox') {
            payload[field.key] = input.checked;
            return;
        }

        if (field.type === 'number' || field.type === 'api-select') {
            payload[field.key] = input.value === '' ? null : Number(input.value);
            return;
        }

        payload[field.key] = input.value === '' ? null : input.value;
    });

    return payload;
}

function formatErrors(payload, status = 422) {
    if (status === 401) {
        return 'No autenticado. Inicia sesión para operar este módulo.';
    }

    if (status === 403) {
        return 'No autorizado. Tu rol/permisos no permiten esta acción.';
    }

    if (!payload?.errors || typeof payload.errors !== 'object') {
        return payload?.message || 'Error en la solicitud.';
    }

    const messages = Object.entries(payload.errors)
        .flatMap(([, values]) => values)
        .map((item) => `- ${item}`);

    return messages.join('\n');
}

function renderIndexTable(config, records, root) {
    const indexUrl = root.dataset.indexUrl;
    const rows = records
        .map((record) => {
            const cells = config.listColumns
                .map((column) => `<td>${escapeHtml(record?.[column])}</td>`)
                .join('');

            return `<tr>
                ${cells}
                <td class="crud-row-actions">
                    <a class="btn btn-secondary" href="${indexUrl}/${record.id}">Ver</a>
                    <a class="btn btn-secondary" href="${indexUrl}/${record.id}/edit">Editar</a>
                    <button class="btn btn-danger" data-delete-id="${record.id}" type="button">Eliminar</button>
                </td>
            </tr>`;
        })
        .join('');

    const headers = config.listColumns
        .map((column) => `<th>${escapeHtml(column)}</th>`)
        .join('');

    return `<div class="table-wrap">
        <table class="data-table">
            <thead>
                <tr>${headers}<th>Acciones</th></tr>
            </thead>
            <tbody>${rows}</tbody>
        </table>
    </div>`;
}

function renderShow(record, indexUrl) {
    const rows = Object.entries(record)
        .map(([key, value]) => `<div class="crud-detail-row"><dt>${escapeHtml(key)}</dt><dd>${escapeHtml(typeof value === 'object' ? JSON.stringify(value) : value)}</dd></div>`)
        .join('');

    return `<dl class="crud-details">${rows}</dl>
        <div class="crud-actions">
            <a class="btn btn-secondary" href="${indexUrl}/${record.id}/edit">Editar</a>
            <a class="btn btn-secondary" href="${indexUrl}">Volver</a>
        </div>`;
}

function renderForm(fields, submitLabel, data = {}) {
    const formFields = fields
        .map((field) => `<div class="crud-field">${renderField(field, data[field.key])}</div>`)
        .join('');

    return `<form class="crud-form" data-crud-form>
        <div class="crud-grid">${formFields}</div>
        <div class="crud-actions">
            <button class="btn btn-primary" type="submit">${submitLabel}</button>
        </div>
    </form>`;
}

async function initCrudRoot(root, modules) {
    const moduleName = root.dataset.module;
    const mode = root.dataset.mode;
    const recordId = root.dataset.recordId;
    const content = root.querySelector('[data-crud-content]');
    const feedback = root.querySelector('[data-crud-feedback]');
    const indexUrl = root.dataset.indexUrl;
    const config = modules[moduleName];

    if (!content || !feedback || !config) {
        return;
    }

    const setFeedback = (message, isError = false) => {
        feedback.textContent = message;
        feedback.classList.toggle('error', isError);
        feedback.classList.toggle('success', !isError && message !== '');
    };

    try {
        if (mode === 'index') {
            const payload = await apiRequest(config.endpoint);
            const records = normalizePayload(payload) || [];
            content.innerHTML = renderIndexTable(config, records, root);

            content.querySelectorAll('[data-delete-id]').forEach((button) => {
                button.addEventListener('click', async () => {
                    const id = button.getAttribute('data-delete-id');
                    if (!window.confirm(`Eliminar registro #${id}?`)) {
                        return;
                    }
                    try {
                        await apiRequest(`${config.endpoint}/${id}`, 'DELETE');
                        window.location.reload();
                    } catch (error) {
                        setFeedback(formatErrors(error.payload, error.status), true);
                    }
                });
            });
            return;
        }

        if (mode === 'create') {
            content.innerHTML = renderForm(config.createFields, `Crear ${config.title}`);
            const form = content.querySelector('[data-crud-form]');
            if (!form) {
                return;
            }

            await hydrateApiSelects(form, config.createFields, {}, setFeedback);

            form.addEventListener('submit', async (event) => {
                event.preventDefault();
                try {
                    const payload = collectFormData(form, config.createFields);
                    const response = await apiRequest(config.endpoint, 'POST', payload);
                    const record = normalizePayload(response);
                    window.location.assign(`${indexUrl}/${record.id}`);
                } catch (error) {
                    setFeedback(formatErrors(error.payload, error.status), true);
                }
            });
            return;
        }

        const payload = await apiRequest(`${config.endpoint}/${recordId}`);
        const record = normalizePayload(payload);

        if (mode === 'show') {
            content.innerHTML = renderShow(record, indexUrl);
            return;
        }

        if (mode === 'edit') {
            content.innerHTML = renderForm(config.editFields, `Guardar ${config.title}`, record);
            const form = content.querySelector('[data-crud-form]');
            if (!form) {
                return;
            }

            await hydrateApiSelects(form, config.editFields, record, setFeedback);

            form.addEventListener('submit', async (event) => {
                event.preventDefault();
                try {
                    const body = collectFormData(form, config.editFields);
                    await apiRequest(`${config.endpoint}/${recordId}`, 'PUT', body);
                    window.location.assign(`${indexUrl}/${recordId}`);
                } catch (error) {
                    setFeedback(formatErrors(error.payload, error.status), true);
                }
            });
        }
    } catch (error) {
        setFeedback(formatErrors(error.payload, error.status), true);
    }
}

export function initCompanyCrudPages() {
    const modules = getModulesConfig();
    document.querySelectorAll('[data-crud-root]')
        .forEach((root) => {
            initCrudRoot(root, modules);
        });
}

