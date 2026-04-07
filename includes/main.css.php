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
        font-size: 10px;
        color: #888;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        margin-bottom: 6px;
    }

    .kpi-value {
        font-family: 'Lora', serif;
        font-size: 15px;
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

    .chart-container {
    position: relative;
    width: 200px;
    margin: 0 auto;
    } 

    .chart-label {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    text-align: center;
    }
    
    .protection-wrapper {
        width: 100%;
        margin-top: 20px;
    }

    .protection-table {
        width: 100%;
        border-spacing: 12px 0;
        margin-left: -12px; 
    }

    .protection-card {
        background: #fcfaf8;
        border: 1px solid #f0ebe5;
        border-radius: 18px;
        padding: 20px;
        width: 50%;
        vertical-align: top;
    }

    .protection-card-title {
        font-weight: 700;
        font-size: 16px;
        color: #3d3229;
        margin-bottom: 4px;
    }

    .protection-card-value {
        font-size: 15px;
        font-weight: 700;
        color: #3d3229;
        margin-bottom: 12px;
    }

    .badge-container {
        margin-bottom: 12px;
    }

    .badge-item {
        display: inline-block;
        background: #D6B89E; 
        color: white;
        padding: 3px 10px;
        border-radius: 6px;
        font-size: 9px;
        font-weight: 600;
        margin-right: 4px;
        margin-bottom: 4px;
        text-transform: uppercase;
    }

    .protection-date {
        font-size: 11px;
        color: #8c8c8c;
    }

    .goal-row {
        background: white;
        border: 1px solid #f0ebe5;
        border-radius: 18px;
        padding: 15px 20px;
        margin-bottom: 12px;
        width: 100%;
    }

    .goal-icon-circle {
        width: 40px;
        height: 40px;
        background: #fcfaf8;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #D6B89E;
        font-size: 20px;
        font-weight: bold;
    }

    .goal-meta {
        font-size: 11px;
        color: #8c8c8c;
        margin-top: 2px;
    }

    .goal-progress-bar {
        background: #fcfaf8;
        padding: 8px 15px;
        border-radius: 10px;
        margin-top: 10px;
        font-size: 11px;
        color: #3d3229;
    }

    .goal-amount {
        font-weight: 700;
        color: #3d3229;
        font-size: 15px;
    }

    .p7-box {
    background-color: #4d4540;
    border-radius: 25px;
    padding: 20px 30px;
    color: white;
    display: flex;
    justify-content: space-between;
    align-items: center;
    width: 100%;
    box-sizing: border-box;
    margin-bottom: 20px;
    }

    .timeline-section {
    padding-top: 10px;
    }

    .timeline-wrapper {
    position: relative;
    padding-left: 40px;
    border-left: 2px dashed #f0ebe5;
    margin-left: 60px;
    margin-top: 40px;
    }

    .timeline-year {
    position: absolute;
    left: -1px;
    transform: translateX(-50%);
    top: -30;
    background: white;
    padding: 0px 5px;
    font-weight: 700;
    color: #3d3229;
    font-size: 14px;
    white-space: nowrap;
    }

    .invest-card {
    background: white;
    border: 1px solid #f0ebe5;
    border-radius: 20px;
    padding: 12px 20px;
    margin-bottom: 12px;
    width: 100%;
    box-sizing: border-box;
    display: table;
    }

    .invest-amount-box {
    background: #fcfaf8;
    border-radius: 12px;
    padding: 10px 15px;
    width: 150px;
    text-align: left;
    }

    /* PAGE 8 */
    .timeline-container {
        position: relative;
        padding-left: 60px;
        margin-top: 40px;
    }

    .timeline-line {
        position: absolute;
        left: 25px;
        top: 0;
        bottom: 0;
        width: 1px;
        border-left: 1px dashed #d0d0d0;
    }

    .timeline-year-label {
        position: absolute;
        left: -60px;
        width: 45px;
        text-align: right;
        font-weight: 700;
        color: #3d3229;
        font-size: 14px;
        line-height: 50px; 
    }

    .timeline-card {
        background: #fff;
        border: 1px solid #2ecc71; 
        border-radius: 12px;
        padding: 12px 20px;
        margin-bottom: 20px;
        position: relative;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .timeline-card--future {
        border-color: #f0ebe5; 
    }

    .timeline-card-main {
        display: flex;
        align-items: center;
        gap: 20px;
    }

    .timeline-icon-circle {
        width: 36px;
        height: 36px;
        border: 1px solid #2ecc71;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #2ecc71;
    }

    .timeline-card-info h4 {
        margin: 0;
        font-size: 15px;
        color: #3d3229;
    }

    .timeline-card-info span {
        font-size: 11px;
        color: #2ecc71;
        font-weight: 600;
    }

    .timeline-card-right {
        display: flex;
        gap: 10px;
    }

    .tag-box {
        border: 1px solid #dcdcdc;
        padding: 4px 10px;
        border-radius: 6px;
        font-size: 11px;
        color: #3d3229;
    }
</style>