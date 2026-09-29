<x-filament-panels::page.simple>

    <style>
        /* Fundo geral */
        body,
        .fi-simple-layout,
        .fi-simple-main-ctn,
        .fi-body {
            background: #ccc !important;
        }

        /* Esconder título e subtítulo do Filament */
        .fi-simple-layout>header,
        .fi-simple-main-ctn>header,
        [class*="fi-header"] {
            display: none !important;
        }

        header.fi-simple-header {
            display: none !important;
        }

        /* Reset do layout Filament */
        .fi-simple-layout {
            display: block !important;
        }

        .fi-simple-main-ctn {
            display: block !important;
        }

        .fi-simple-main {
            max-width: 100% !important;
            width: 100% !important;
            margin: 0 !important;
            padding: 0 !important;
            border-radius: 0 !important;
            background: transparent !important;
            box-shadow: none !important;
        }

        /* Wrapper principal */
        .login-wrapper {
            background: #f5f5f5;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Container do card */
        .login-container {
            width: 95%;
            max-width: 1700px;
            min-height: 650px;
            display: flex;
            border-radius: 10px;
            overflow: hidden;
        }

        /* LEFT */
        .login-left {
            width: 35%;
            padding: 60px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            background: #f5f5f5;
        }

        /* RIGHT */
        .login-right {
            width: 65%;
            background: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-right img {
            max-width: 90%;
            height: auto;
        }

        /* MOBILE */
        @media (max-width: 1100px) {
            .login-right {
                display: none !important;
            }

            .login-left {
                width: 100% !important;
                padding: 30px !important;
            }

            .login-container {
                flex-direction: column;
                min-height: unset;
            }
        }
    </style>

    <div class="login-wrapper">

        <div class="login-container">

            <!-- LEFT -->
            <div class="login-left">

                <div style="margin-bottom:20px; display: flex; justify-content: center; align-items: center">
                    <img src="{{ asset('assets/images/vl-sistemas.jpeg') }}" style="height:56px;">
                </div>

                <p style="text-align:center; color:#8687a7; margin-bottom:25px;">
                    Faça login com suas credenciais.
                </p>

                <form wire:submit.prevent="authenticate" style="display:flex; flex-direction:column; gap:20px;">

                    <div>
                        <label style="color:#8687a7; font-size:14px;">E-mail</label>
                        <input type="email" wire:model.defer="data.email"
                            style="width:100%; background:transparent; border:none; border-bottom:1px solid #999; padding:8px 0; outline:none;">
                    </div>

                    <div>
                        <label style="color:#8687a7; font-size:14px;">Senha</label>
                        <input type="password" wire:model.defer="data.password"
                            style="width:100%; background:transparent; border:none; border-bottom:1px solid #999; padding:8px 0; outline:none;">
                    </div>

                    <div style="display:flex; justify-content:space-between; font-size:14px; color:#8687a7;">
                        <label style="display:flex; gap:8px; align-items:center; cursor:pointer;">
                            <style>
                                .remember-checkbox {
                                    appearance: none;
                                    -webkit-appearance: none;
                                    width: 18px;
                                    height: 18px;
                                    border: 2px solid #ccc;
                                    border-radius: 50%;
                                    cursor: pointer;
                                    transition: all 0.2s ease;
                                    flex-shrink: 0;
                                }

                                .remember-checkbox:checked {
                                    background-color: #f47c20;
                                    border-color: #f47c20;
                                    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 12 12'%3E%3Cpath fill='white' d='M2 6l3 3 5-5' stroke='white' stroke-width='1.5' stroke-linecap='round' stroke-linejoin='round' fill='none'/%3E%3C/svg%3E");
                                    background-repeat: no-repeat;
                                    background-position: center;
                                    background-size: 70%;
                                }

                                .remember-checkbox:hover {
                                    border-color: #f47c20;
                                }
                            </style>
                            <input type="checkbox" class="remember-checkbox" wire:model.defer="data.remember">
                            Lembrar de mim
                        </label>
                        <a href="#" style="color:#8687a7; text-decoration:none;">Esqueceu sua senha?</a>
                    </div>

                    <div style="display:flex; justify-content:center;">
                        <button type="submit"
                            style="background:#f47c20; border:none; border-radius:30px; padding:10px 48px; color:#fff; font-weight:bold; font-size:13px; cursor:pointer; letter-spacing:1px; transition: background 0.2s ease;"
                            onmouseover="this.style.background='#d96c10'" onmouseout="this.style.background='#f47c20'">
                            ACESSAR
                        </button>
                    </div>

                </form>

            </div>

            <!-- RIGHT -->
            <div class="login-right">
                <img src="{{ asset('assets/images/vl-catalogo.png') }}">
            </div>

        </div>

    </div>

</x-filament-panels::page.simple>
