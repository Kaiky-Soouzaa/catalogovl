<x-filament-widgets::widget>
    <div class="product-shopify-toolbar"
        style="
        width:100%;
        border:1px solid #E5E7EB;
        border-radius:18px 18px 0 0;
        background:#fff;
        padding:16px 20px;
        box-shadow:none;
        margin-bottom:0;
    ">
        <div
            style="
                display:flex;
                align-items:center;
                justify-content:space-between;
                gap:16px;
                flex-wrap:wrap;
            ">
            <div
                style="
                    width:380px;
                    max-width:100%;
                    height:44px;
                    border:1px solid #E5E7EB;
                    border-radius:12px;
                    background:#fff;
                    display:flex;
                    align-items:center;
                    padding:0 14px;
                    gap:10px;
                    color:#94A3B8;
                ">
                <x-heroicon-o-magnifying-glass style="width:20px;height:20px;" />

                <input type="text" placeholder="Pesquisar produtos..."
                    style="
                        width:100%;
                        border:none;
                        outline:none;
                        background:transparent;
                        font-size:14px;
                        color:#111827;
                    ">
            </div>

            <div style="display:flex;align-items:center;gap:10px;">
                <button type="button"
                    style="
                        height:44px;
                        border:1px solid #E5E7EB;
                        border-radius:12px;
                        padding:0 16px;
                        background:#fff;
                        display:flex;
                        align-items:center;
                        gap:8px;
                        font-size:14px;
                        font-weight:600;
                        color:#374151;
                        cursor:pointer;
                    ">
                    <x-heroicon-o-funnel style="width:18px;height:18px;" />
                    Filtros
                </button>

                <button type="button"
                    style="
                        width:44px;
                        height:44px;
                        border:1px solid #E5E7EB;
                        border-radius:12px;
                        background:#fff;
                        display:flex;
                        align-items:center;
                        justify-content:center;
                        color:#64748B;
                        cursor:pointer;
                    ">
                    <x-heroicon-o-view-columns style="width:20px;height:20px;" />
                </button>
            </div>
        </div>
    </div>
</x-filament-widgets::widget>
