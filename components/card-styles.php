<style>
    :root {
        --clr-primary: #927355;
    }

    .page {
        width: 210mm;
        min-height: 297mm;
        padding: 20mm;
        box-sizing: border-box;
        font-family: 'Plus Jakarta Sans', sans-serif;
        background: white;
        color: #333;
    }

    .section-heading {
        font-family: 'Lora', serif;
        font-size: 24px;
        color: var(--clr-primary);
        margin: 0 0 18px 0;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .section-block {
        margin-bottom: 40px;
    }

    .sol-card {
        background: #fff;
        border: 1px solid #f0ebe5;
        border-radius: 16px;
        padding: 20px;
        margin-bottom: 18px;
    }

    .sol-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 18px;
    }

    .sol-name {
        font-family: 'Lora', serif;
        font-size: 20px;
        color: var(--clr-primary);
    }

    .tag {
        display: inline-block;
        font-size: 9px;
        text-transform: uppercase;
        border: 1px solid #d4c4b5;
        padding: 3px 8px;
        border-radius: 5px;
        color: var(--clr-primary);
        margin: 2px;
    }

    .sol-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 12px;
    }

    .sol-item {
        background: #fcfaf8;
        border-radius: 10px;
        padding: 12px;
    }

    .sol-item-label {
        font-size: 10px;
        color: #aaa;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        margin-bottom: 5px;
    }

    .sol-item-value {
        font-size: 15px;
        font-weight: bold;
        color: #333;
    }

    .sol-item-value.highlight {
        color: var(--clr-primary);
        font-family: 'Lora', serif;
        font-size: 18px;
    }

    .progress-wrap {
        margin-bottom: 16px;
    }

    .progress-row {
        display: flex;
        justify-content: space-between;
        font-size: 11px;
        color: #888;
        margin-bottom: 5px;
    }

    .progress-bar-outer {
        background: #f3f3f3;
        height: 10px;
        border-radius: 5px;
        overflow: hidden;
    }

    .progress-bar-inner {
        height: 100%;
        border-radius: 5px;
        background: var(--clr-primary);
    }

    .progress-pct {
        text-align: right;
        font-size: 11px;
        color: var(--clr-primary);
        margin-top: 4px;
        font-weight: bold;
    }

    .diverz-box {
        background: var(--clr-primary);
        color: white;
        border-radius: 20px;
        padding: 28px;
        display: flex;
        align-items: center;
        gap: 25px;
        margin-top: 30px;
    }

    .diverz-icon {
        font-size: 40px;
        opacity: 0.7;
    }

    .diverz-text h3 {
        margin: 0 0 6px 0;
        font-family: 'Lora', serif;
        font-size: 20px;
    }

    .diverz-text p {
        margin: 0;
        font-size: 12px;
        opacity: 0.85;
        line-height: 1.5;
    }
</style>