<style>
    :root {
        --clr-primary: #927355;
        --clr-gray: #8c8c8c;
    }

    body {
        margin: 0;
        padding: 0;
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
    }

    .no-print {
        display: none !important;
    }

    .page-break {
        page-break-after: always;
    }

    .page {
        width: 100%;
        min-height: 257mm;
        padding: 0;
        box-sizing: border-box;
        background: white;
        font-family: 'Plus Jakarta Sans', sans-serif;
        page-break-after: always;
        break-after: page;
        overflow: visible;
    }

    .main-title {
        font-family: 'Lora', serif;
        font-size: 42px;
        line-height: 1.1;
        margin: 0 0 8px 0;
    }

    .gray {
        color: var(--clr-gray);
    }

    .section-title {
        font-family: 'Lora', serif;
        font-size: 20px;
        color: var(--clr-primary);
        margin: 30px 0 15px 0;
        border-bottom: 1px solid #f0f0f0;
        padding-bottom: 8px;
    }

    .kpi-grid {
        display: flex;
        gap: 15px;
        margin-bottom: 30px;
    }

    .kpi-card {
        flex: 1;
        background: #fcfaf8;
        border: 1px solid #f0ebe5;
        border-radius: 14px;
        padding: 18px;
    }

    .kpi-label {
        font-size: 11px;
        color: #888;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        margin-bottom: 6px;
    }

    .kpi-value {
        font-family: 'Lora', serif;
        font-size: 22px;
        color: var(--clr-primary);
        font-weight: bold;
    }

    .kpi-value.neg {
        color: #c0392b;
    }

    .kpi-value.pos {
        color: #27ae60;
    }

    .info-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 10px;
    }

    .info-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: #fff;
        border: 1px solid #f0f0f0;
        border-radius: 10px;
        padding: 12px 16px;
    }

    .info-label {
        font-size: 12px;
        color: #888;
    }

    .info-val {
        font-size: 14px;
        font-weight: bold;
        color: #333;
    }

    .bilance-bar-wrap {
        margin-top: 30px;
    }

    .bilance-bar-row {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 10px;
    }

    .bilance-bar-label {
        width: 80px;
        font-size: 12px;
        color: #666;
    }

    .bilance-bar-outer {
        flex: 1;
        background: #f3f3f3;
        height: 16px;
        border-radius: 8px;
        overflow: hidden;
    }

    .bilance-bar-inner {
        height: 100%;
        border-radius: 8px;
    }

    .page-subtitle {
        color: #888;
        margin: 0 0 25px 0;
    }

    .section-icon {
        width: 38px;
        height: 38px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .section-icon--primary {
        background: var(--clr-primary);
        color: white;
    }

    .bilance-bar-amount {
        width: 110px;
        text-align: right;
        font-size: 12px;
        font-weight: bold;
        color: #444;
    }
</style>