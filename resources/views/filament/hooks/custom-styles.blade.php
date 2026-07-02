<style>
    /* SIDEBAR */
    .fi-sidebar {
        border-right: 1px solid #f1f5f9 !important;
        background: #ffffff !important;
    }

    .fi-sidebar-header {
        border-bottom: 1px solid #f3f4f6 !important;
        padding-top: 14px !important;
        padding-bottom: 14px !important;
    }

    .fi-sidebar-nav {
        padding: 18px 10px !important;
    }

    .fi-sidebar-item a {
        border-radius: 12px !important;
        padding: 11px 12px !important;
        transition: all .18s ease !important;
    }

    .fi-sidebar-item a:hover,
    .fi-sidebar-item-active a {
        background: #fff7ed !important;
        color: #f97316 !important;
    }

    .fi-sidebar-item a:hover svg,
    .fi-sidebar-item a:hover span,
    .fi-sidebar-item-active svg,
    .fi-sidebar-item-active span {
        color: #f97316 !important;
    }

    .fi-sidebar-item-active a {
        font-weight: 700 !important;
        position: relative;
    }

    .fi-sidebar-item-active a::before {
        content: "";
        width: 4px;
        height: 24px;
        border-radius: 999px;
        background: #f97316;
        position: absolute;
        left: -6px;
        top: 50%;
        transform: translateY(-50%);
    }

    .fi-sidebar-item svg {
        width: 21px !important;
        height: 21px !important;
    }

    .fi-sidebar-item-label {
        font-weight: 600 !important;
    }

    .fi-sidebar-group-label {
        color: #94a3b8 !important;
        font-size: 11px !important;
        font-weight: 700 !important;
        text-transform: uppercase !important;
        letter-spacing: .06em !important;
    }

    .fi-badge {
        border-radius: 999px !important;
        font-weight: 700 !important;
    }

    /* DASHBOARD CARDS SUPERIORES */
    .dashboard-stats-grid {
        width: 100% !important;
        max-width: 100% !important;
    }

    @media (max-width: 1500px) {
        .dashboard-stats-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
        }
    }

    @media (max-width: 900px) {
        .dashboard-stats-grid {
            grid-template-columns: 1fr !important;
        }
    }

    /* ÚLTIMOS PEDIDOS */
    .latest-orders-scroll {
        flex: 1;
        width: 100%;
        border: 1px solid #e5e7eb;
        border-radius: 18px;
        background: #fff;
        overflow-x: hidden;
        overflow-y: hidden;
    }

    .latest-orders-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 14px;
        table-layout: auto;
    }

    .latest-orders-table th,
    .latest-orders-table td {
        white-space: nowrap;
    }

    @media (max-width: 1500px) {
        .latest-orders-scroll {
            overflow-x: auto !important;
            -webkit-overflow-scrolling: touch;
        }

        .latest-orders-table {
            min-width: 760px !important;
        }
    }

    @media (min-width: 1501px) {
        .latest-orders-scroll {
            overflow-x: hidden !important;
        }

        .latest-orders-table {
            width: 100% !important;
            min-width: 0 !important;
        }
    }

    /* RESUMO FINANCEIRO */
    .financial-summary-grid {
        display: grid !important;
        grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
        gap: 16px !important;
        align-items: stretch !important;
    }

    .financial-summary-card {
        width: 100% !important;
        min-width: 0 !important;
        min-height: 120px !important;
        height: 120px !important;
        border: 1px solid #e5e7eb;
        border-radius: 18px;
        background: #fff;
        padding: 20px;
        box-shadow: 0 8px 24px rgba(15, 23, 42, .06);
        display: flex !important;
        align-items: center !important;
        justify-content: space-between !important;
        gap: 12px !important;
        overflow: hidden !important;
    }

    .financial-summary-info {
        min-width: 0 !important;
        flex: 1 1 auto !important;
    }

    .financial-summary-label {
        font-size: 14px;
        line-height: 1.25;
        color: #6b7280;
        margin: 0;
    }

    .financial-summary-value {
        font-size: 22px;
        font-weight: 800;
        margin: 8px 0 0;
        line-height: 1.15;
        color: #111827;
        white-space: nowrap !important;
    }

    .financial-summary-icon {
        width: 44px;
        height: 44px;
        min-width: 44px;
        border-radius: 999px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 800;
        font-size: 18px;
        flex-shrink: 0 !important;
    }

    @media (max-width: 900px) {
        .financial-summary-grid {
            grid-template-columns: 1fr !important;
        }

        .financial-summary-card {
            height: auto !important;
            min-height: 110px !important;
        }
    }

    /* CARDS PRODUTOS */
    .product-stats-grid {
        display: grid !important;
        grid-template-columns: repeat(5, minmax(0, 1fr)) !important;
        gap: 20px !important;
        width: 100% !important;
        max-width: 100% !important;
    }

    .product-stat-card {
        min-width: 0 !important;
        min-height: 110px;
        border: 1px solid #e5e7eb;
        border-radius: 18px;
        background: #fff;
        padding: 22px;
        box-shadow: 0 8px 24px rgba(15, 23, 42, .04);
        display: flex;
        align-items: center;
        gap: 16px;
    }

    @media (max-width: 1400px) {
        .product-stats-grid {
            grid-template-columns: repeat(3, minmax(0, 1fr)) !important;
        }
    }

    @media (max-width: 1024px) {
        .product-stats-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
        }
    }

    @media (max-width: 640px) {
        .product-stats-grid {
            grid-template-columns: 1fr !important;
        }
    }

    /* TABELA FILAMENT - APENAS VISUAL */
    .fi-ta-header-cell {
        background: #f8fafc !important;
    }

    .fi-ta-header-cell-label {
        color: #64748b !important;
        font-weight: 600 !important;
        font-size: 13px !important;
    }

    .fi-ta-header-toolbar {
        padding: 18px 22px !important;
    }

    .fi-ta-search-field {
        max-width: 420px !important;
    }

    .fi-ta-search-field .fi-input-wrp {
        border-radius: 12px !important;
        height: 44px !important;
    }

    .fi-ta-header-toolbar button {
        border-radius: 12px !important;
        height: 44px !important;
    }

    .fi-ta-row:hover {
        background: #fafafa !important;
    }














    /* CARDS PEDIDOS */
    .order-stats-grid {
        display: grid;
        grid-template-columns: repeat(5, minmax(0, 1fr));
        gap: 20px;
    }

    .order-dashboard-card {
        position: relative;
        overflow: hidden;
        min-height: 132px;
        border: 1px solid #e5e7eb;
        border-radius: 18px;
        background: #ffffff;
        padding: 20px 22px;
        box-shadow: 0 8px 24px rgba(15, 23, 42, .04);
        text-decoration: none;
        transition: all .18s ease;
    }

    .order-dashboard-card:hover {
        transform: translateY(-2px);
        border-color: var(--card-color);
        box-shadow: 0 12px 28px rgba(15, 23, 42, .08);
    }

    .order-dashboard-card-active {
        border-color: var(--card-color) !important;
        box-shadow: 0 12px 28px rgba(15, 23, 42, .08);
    }

    .order-dashboard-icon {
        width: 48px;
        height: 48px;
        min-width: 48px;
        border-radius: 14px;
        background: var(--card-bg);
        color: var(--card-color);
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .order-dashboard-title {
        font-size: 14px;
        color: #6b7280;
        margin: 0;
        font-weight: 600;
        line-height: 1.25;
    }

    .order-dashboard-value {
        font-size: 30px;
        font-weight: 800;
        color: #020617;
        margin: 8px 0 0;
        line-height: 1;
    }

    .order-dashboard-line {
        position: absolute;
        right: 16px;
        bottom: 10px;
        width: 130px;
        height: 44px;
    }

    @media (max-width: 1400px) {
        .order-stats-grid {
            grid-template-columns: repeat(3, minmax(0, 1fr));
        }
    }

    @media (max-width: 1024px) {
        .order-stats-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (max-width: 640px) {
        .order-stats-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
