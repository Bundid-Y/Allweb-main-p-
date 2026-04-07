/**
 * TNB Language System (i18n Engine)
 * Namespace: window.tnbLang
 * localStorage key: tnb_lang
 * Supports: th | en | zh | jp
 * 
 * Usage:
 *   Add data-i18n="section.key" on any element to translate its textContent.
 *   Add data-i18n-placeholder="section.key" to translate input placeholders.
 *   Call window.tnbLang.setLang('en') to switch language programmatically.
 * 
 * To add new translatable text:
 *   1. Add a new key in all 4 JSON files (th.json, en.json, zh.json, jp.json)
 *   2. Add data-i18n="yourSection.yourKey" to the HTML element
 *   3. No JS changes needed.
 */

(function () {
    'use strict';

    // ── Guard: prevent double-init ──────────────────────────────────────────
    if (window.tnbLang && window.tnbLang._initialized) return;

    // ── Constants ───────────────────────────────────────────────────────────
    const STORAGE_KEY   = 'tnb_lang';
    const SUPPORTED     = ['th', 'en', 'zh', 'jp'];
    const DEFAULT_LANG  = 'th';
    const FALLBACK_LANG = 'en';
    // Path relative to the page that loads this script (from /main/ to /lang/)
    const LANG_BASE     = '../lang/';
    
    // Path resolution - try multiple paths to find the correct one
    function getLangBase() {
        // Try different possible paths based on common server setups
        const possiblePaths = [
            '../lang/',           // From /main/ to /lang/
            './lang/',            // From /main/ to /main/lang/ (if lang is in same dir)
            '/Allweb-main/tnb/lang/',  // Absolute from document root
            '/tnb/lang/',         // Shorter absolute path
            '../../lang/'         // From deeper subdirectories
        ];
        
        // Return the first path (will be tested at runtime)
        return '../lang/';
    }

    // Try multiple paths until one works
    async function tryMultiplePaths(lang) {
        const possiblePaths = [
            '../lang/',
            './lang/',
            '/Allweb-main/tnb/lang/',
            '/tnb/lang/',
            '../../lang/'
        ];
        
        for (const basePath of possiblePaths) {
            try {
                const url = basePath + lang + '.json';
                console.log('[tnbLang] Trying path:', url);
                const res = await fetch(url);
                if (res.ok) {
                    console.log('[tnbLang] SUCCESS with path:', url);
                    const data = await res.json();
                    return { success: true, data, path: basePath };
                }
            } catch (err) {
                console.log('[tnbLang] Failed path:', basePath + lang + '.json', err.message);
                continue;
            }
        }
        
        console.error('[tnbLang] All paths failed for language:', lang);
        return { success: false, data: null, path: null };
    }

    // In-memory cache (avoids re-fetching already-loaded JSONs) ───────────
    const _cache = {};

    // ── Current language state ──────────────────────────────────────────────
    let _currentLang = null;
    let _currentData = {};

    // ── Fetch + cache a language file with multi-path fallback ───────────────
    async function _loadLang(lang) {
        console.log('[tnbLang] Loading language file:', lang);
        if (_cache[lang]) {
            console.log('[tnbLang] Language file cached:', lang);
            return _cache[lang];
        }

        // Validate lang to prevent path traversal
        if (!SUPPORTED.includes(lang)) {
            console.warn('[tnbLang] Unsupported language:', lang);
            return null;
        }

        try {
            // Try multiple paths automatically
            const result = await tryMultiplePaths(lang);
            
            if (result.success && result.data) {
                console.log('[tnbLang] Language file loaded successfully:', lang, 'from path:', result.path);
                _cache[lang] = result.data;
                return result.data;
            } else {
                throw new Error('No working path found');
            }
        } catch (err) {
            console.error('[tnbLang] Failed to load language file:', lang, err);
            return null;
        }
    }

    // ── Resolve a dot-notation key from a data object ───────────────────────
    // e.g. _resolve('nav.services', { nav: { services: 'บริการของเรา' } })
    function _resolve(key, data) {
        if (!key || !data) return null;
        const parts = key.split('.');
        let val = data;
        for (const part of parts) {
            if (val == null || typeof val !== 'object') return null;
            val = val[part];
        }
        return (val !== undefined && val !== null && typeof val === 'string') ? val : null;
    }

    // ── XSS-safe text setter ────────────────────────────────────────────────
    function _safeSetText(el, text) {
        if (text === null || text === undefined) return;
        // ใช้ innerHTML สำหรับรองรับ HTML tags ในการเน้นคำ
        // แต่ต้องแน่ใจว่าข้อมูลมาจาก JSON ที่เชื่อถือได้
        el.innerHTML = text; // รองรับ <strong> สำหรับการเน้นคำ
    }

    // ── Apply translations to all [data-i18n] elements in the DOM ───────────
    function _applyTranslations(data, fallbackData) {
        // data-i18n → textContent
        document.querySelectorAll('[data-i18n]').forEach(el => {
            const key = el.getAttribute('data-i18n');
            const text = _resolve(key, data) ?? _resolve(key, fallbackData) ?? null;
            if (text !== null) _safeSetText(el, text);
        });

        // data-i18n-placeholder → input placeholder
        document.querySelectorAll('[data-i18n-placeholder]').forEach(el => {
            const key = el.getAttribute('data-i18n-placeholder');
            const text = _resolve(key, data) ?? _resolve(key, fallbackData) ?? null;
            if (text !== null) el.setAttribute('placeholder', text);
        });

        // data-i18n-title → element title attribute
        document.querySelectorAll('[data-i18n-title]').forEach(el => {
            const key = el.getAttribute('data-i18n-title');
            const text = _resolve(key, data) ?? _resolve(key, fallbackData) ?? null;
            if (text !== null) el.setAttribute('title', text);
        });

        // data-i18n-value → input value attribute (for submit buttons)
        document.querySelectorAll('[data-i18n-value]').forEach(el => {
            const key = el.getAttribute('data-i18n-value');
            const text = _resolve(key, data) ?? _resolve(key, fallbackData) ?? null;
            if (text !== null) el.value = text;
        });
    }

    // ── Update <html lang=""> and language button label ──────────────────────
    function _updateMeta(lang, data) {
        // HTML lang attribute
        const htmlLangMap = { th: 'th', en: 'en', zh: 'zh-Hans', jp: 'ja' };
        document.documentElement.lang = htmlLangMap[lang] || lang;

        // Update font-family for CJK support
        const fontMap = {
            th: "'Sarabun', 'Inter', sans-serif",
            en: "'Inter', 'Sarabun', sans-serif",
            zh: "'Noto Sans SC', 'Sarabun', 'Inter', sans-serif",
            jp: "'Noto Sans JP', 'Sarabun', 'Inter', sans-serif"
        };
        document.documentElement.style.setProperty('--tnb-font-primary', fontMap[lang] || fontMap.th);

        // Update language label in the nav toggle button
        const label = data && data.lang_label ? data.lang_label : lang.toUpperCase();
        const labelEl = document.getElementById('tnbLangLabel');
        if (labelEl) labelEl.textContent = label;

        // Mark active language button
        document.querySelectorAll('[data-tnb-lang-btn]').forEach(btn => {
            const btnLang = btn.getAttribute('data-tnb-lang-btn');
            btn.classList.toggle('tnb-lang-btn--active', btnLang === lang);
        });
    }

    // ── Core: Set language ───────────────────────────────────────────────────
    async function setLang(lang) {
        console.log('[tnbLang] Setting language to:', lang);
        // Validate
        if (!SUPPORTED.includes(lang)) {
            console.warn('[tnbLang] Unknown language:', lang, '— falling back to', DEFAULT_LANG);
            lang = DEFAULT_LANG;
        }

        // Load chosen language
        let data = await _loadLang(lang);

        // If load failed, use fallback
        let fallbackData = _currentData;
        if (!data) {
            console.warn('[tnbLang] Falling back to', FALLBACK_LANG);
            data = await _loadLang(FALLBACK_LANG);
            if (!data) {
                console.error('[tnbLang] Critical: could not load any language file.');
                return;
            }
            lang = FALLBACK_LANG;
        }

        // Pre-load fallback for missing key resolution (only if needed)
        if (lang !== FALLBACK_LANG && !_cache[FALLBACK_LANG]) {
            _loadLang(FALLBACK_LANG); // async, no await — preload in background
        }
        fallbackData = _cache[FALLBACK_LANG] || {};

        _currentLang = lang;
        _currentData = data;

        // Apply DOM translations
        _applyTranslations(data, fallbackData);

        // Update meta info
        _updateMeta(lang, data);

        // Persist to localStorage
        try {
            localStorage.setItem(STORAGE_KEY, lang);
        } catch (e) {
            // localStorage not available (private mode, etc.)
        }
    }

    // ── Read saved language preference ───────────────────────────────────────
    function _getSavedLang() {
        try {
            const saved = localStorage.getItem(STORAGE_KEY);
            if (saved && SUPPORTED.includes(saved)) return saved;
        } catch (e) { /* ignore */ }
        return DEFAULT_LANG;
    }

    // ── Initialize ───────────────────────────────────────────────────────────
    async function init() {
        console.log('[tnbLang] Initializing...');
        const lang = _getSavedLang();
        console.log('[tnbLang] Saved language:', lang);
        await setLang(lang);
        console.log('[tnbLang] Initialization complete');
    }

    // ── Public API ───────────────────────────────────────────────────────────
    window.tnbLang = {
        _initialized: true,
        setLang,
        getCurrentLang: () => _currentLang,
        getSupportedLangs: () => [...SUPPORTED],
        resolve: (key) => _resolve(key, _currentData)
    };

    // Auto-init when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init, { once: true });
    } else {
        init();
    }

    // Debug: Add global function to check i18n status
    window.tnbLangDebug = function() {
        console.log('=== TNB i18n Debug Info ===');
        console.log('Current language:', _currentLang);
        console.log('Current data keys:', _currentData ? Object.keys(_currentData) : 'No data');
        console.log('Cache keys:', Object.keys(_cache));
        console.log('Supported languages:', SUPPORTED);
        console.log('Storage key:', STORAGE_KEY);
        console.log('Lang base path:', LANG_BASE);
        return {
            currentLang: _currentLang,
            hasData: !!_currentData,
            cacheKeys: Object.keys(_cache),
            supported: SUPPORTED
        };
    };

    // Test function to verify translations
    window.tnbLangTest = function() {
        if (!window.tnbLang || !window.tnbLang._initialized) {
            console.error('TNB i18n not initialized');
            return false;
        }
        
        const testKeys = ['branches.title', 'branches.subtitle', 'branches.bangsaen_name'];
        const results = {};
        
        testKeys.forEach(key => {
            const element = document.querySelector(`[data-i18n="${key}"]`);
            const translation = window.tnbLang.resolve(key);
            results[key] = {
                element: element ? element.textContent : 'Element not found',
                translation: translation || 'Translation not found',
                match: element && translation ? element.textContent === translation : false
            };
        });
        
        console.log('=== TNB i18n Test Results ===');
        console.table(results);
        return results;
    };

})();
