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
        margin: 44px 0 20px 0;
        border-bottom: 1px solid #f0f0f0;
        padding-bottom: 8px;
    }

    .section-title--bilance {
        font-size: 32px;
        line-height: 1.15;
        margin-top: 50px;
        margin-bottom: 16px;
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

    .page-subtitle {
        color: #888;
        margin: 0 0 34px 0;
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

    .section-with-chart {
        display: flex;
        gap: 20px;
        align-items: center;
        margin-bottom: 20px;
    }

    .section-text {
        flex: 1;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .donut-container {
        flex: 0 0 auto;
        width: 210px;
        text-align: center;
    }

    .chart-legend {
        font-size: 11px;
        line-height: 1.6;
        margin-top: 10px;
    }

    .legend-item {
        display: flex;
        align-items: center;
        gap: 6px;
        justify-content: center;
        margin-bottom: 4px;
    }

    .legend-color {
        width: 12px;
        height: 12px;
        border-radius: 2px;
        flex-shrink: 0;
    }

    .bilance-layout {
        display: flex;
        gap: 20px;
        align-items: stretch;
        margin-bottom: 14px;
    }

    .bilance-chart-col {
        flex: 0 0 50%;
        max-width: 50%;
        min-width: 0;
    }

    .bilance-chart-wrap {
        position: relative;
        width: 100%;
        height: 305px;
        background: transparent;
        border: 0;
        border-radius: 0;
        padding: 0;
        box-sizing: border-box;
    }

    .bilance-summary-col {
        flex: 1;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .bilance-stat-stack {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    .bilance-stat-item {
        flex: 1;
        min-width: 0;
    }

    .bilance-stat-card {
        border: 1px solid #f0ebe5;
        border-radius: 12px;
        background: #fcfaf8;
        padding: 12px 14px;
    }

    .bilance-stat-card--inline {
        display: flex;
        justify-content: space-between;
        align-items: baseline;
        gap: 12px;
    }

    .bilance-stat-label {
        font-size: 11px;
        color: #777;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        margin-bottom: 0;
    }

    .bilance-stat-value {
        font-family: 'Lora', serif;
        font-size: 20px;
        color: #333;
        font-weight: 700;
        line-height: 1.2;
    }

    .bilance-stat-note {
        margin-top: 6px;
        padding-left: 15px;
        font-size: 11px;
        color: #8a8a8a;
    }

    .bilance-net-box {
        border: 1px solid #e7dfd8;
        border-radius: 12px;
        background: #f7f2ec;
        padding: 14px 16px;
        display: flex;
        justify-content: space-between;
        align-items: baseline;
    }

    .bilance-net-label {
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: #7a6a5c;
    }

    .bilance-net-value {
        font-family: 'Lora', serif;
        font-size: 24px;
        color: #333;
        font-weight: 700;
    }

    .bilance-net-value.pos {
        color: #27ae60;
    }

    .bilance-net-value.neg {
        color: #c0392b;
    }
</style>