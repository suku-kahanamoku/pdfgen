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

    .page {
        width: 100%;
        min-height: 257mm;
        padding: 30px 40px;
        box-sizing: border-box;
        background: white;
        font-family: 'Plus Jakarta Sans', sans-serif;
        page-break-after: always;
        break-after: page;
        overflow: visible;
    }

    .main-title {
        font-family: 'Lora', serif;
        font-size: 47px;
        line-height: 1.1;
        margin: 0 0 8px 0;
    }

    .gray {
        color: var(--clr-gray);
    }

    .page-subtitle {
        color: #888;
        margin: 0 0 25px 0;
    }

    .chart-container {
        position: relative;
        width: 200px;
        margin: 0 auto;
    }

    .p2-sec-block {
        margin-bottom: 30px;
    }

    .p2-sec-title {
        display: flex;
        align-items: center;
        gap: 12px;
        color: var(--clr-primary);
        font-family: 'Lora', serif;
        font-size: 20px;
        margin-bottom: 12px;
        border-bottom: 1px solid #eee;
        padding-bottom: 10px;
    }

    .p2-card-row {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 10px;
        page-break-inside: avoid;
        break-inside: avoid;
    }

    .p2-status-icon {
        border-radius: 50%;
        width: 22px;
        height: 22px;
        display: flex;
        justify-content: center;
        align-items: center;
        font-size: 10px;
        flex-shrink: 0;
        border: 1.2px solid;
    }

    .p2-detail-card {
        flex: 1;
        background: #fff;
        border: 1px solid #f0f0f0;
        border-radius: 12px;
        padding: 14px 16px;
        display: flex;
        gap: 15px;
        align-items: center;
    }

    .p2-price-box {
        background: #f8f8f8;
        padding: 10px 14px;
        border-radius: 8px;
        min-width: 145px;
        flex-shrink: 0;
    }

    .p2-val-amount {
        font-size: 16px;
        color: var(--clr-primary);
        font-family: 'Lora', serif;
        margin-bottom: 2px;
    }

    .p2-val-label {
        font-size: 10px;
        color: #777;
        font-weight: bold;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .p2-middle {
        flex: 1;
        min-width: 0;
    }

    .p2-text-main {
        font-weight: bold;
        font-size: 13px;
        color: #333;
        margin-bottom: 3px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .p2-text-minor {
        font-size: 11px;
        color: #888;
    }

    .p2-tag-cloud {
        display: flex;
        flex-direction: column;
        gap: 5px;
        align-items: flex-end;
        width: 130px;
        flex-shrink: 0;
    }

    .p2-tag {
        font-size: 9px;
        text-transform: uppercase;
        border: 1px solid #d4c4b5;
        padding: 4px 8px;
        border-radius: 6px;
        color: var(--clr-primary);
        text-align: center;
        width: 100%;
        box-sizing: border-box;
        white-space: nowrap;
    }
</style>
