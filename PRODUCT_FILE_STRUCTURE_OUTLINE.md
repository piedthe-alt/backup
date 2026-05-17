# product.blade.php - Detailed Structure Outline (3364 lines)

## 📋 TABLE OF CONTENTS

1. [HTML Document Structure](#html-document-structure)
2. [CSS Styling Sections](#css-styling-sections)
3. [HTML Body - Main Content](#html-body---main-content)
4. [JavaScript Functions](#javascript-functions)
5. [Component Extraction Guide](#component-extraction-guide)

---

## HTML DOCUMENT STRUCTURE

### 1. Document Header & Metadata
- **Lines 1-4**: DOCTYPE & HTML opening tag
- **Lines 5-8**: Meta tags (charset, viewport, title)

### 2. CSS & JS Libraries (External)
- **Lines 10-12**: External CSS links
  - Bootstrap 5.3.3 (line 10)
  - FontAwesome 6.4.0 (line 11)
  - Google Fonts - Inter (line 12)
- **Lines 14-15**: External JavaScript libraries
  - Quagga barcode scanner (line 14)
  - html5-qrcode library (line 15)

### 3. Internal Styles Block
- **Lines 17-1095**: `<style>` section with comprehensive CSS

#### CSS Subsections:
| Section | Line Range | Purpose |
|---------|-----------|---------|
| Root Variables | 22-31 | Color scheme and design tokens |
| Body & General Styles | 33-51 | Background, fonts, transitions |
| Product Card Styles | 114-195 | Card hover effects, layout |
| Header Section | 54-75 | Header gradient and decoration |
| Stat Box Styles | 78-112 | Statistics display styling |
| Modal Header Styles | 361-380 | Modal appearance |
| Scanner Modal Styles | 554-572 | Scanner container styling |
| Quantity Selector | 817-871 | Cart quantity controls |
| Cart Badge | 793-815 | Cart item count badge |
| Cart Styles | 807-906 | Cart modal and item display |
| Responsive Design | 432-542 | Mobile and tablet adjustments |
| Copy Button Animation | 2467-2525 | Copy-to-clipboard feedback |
| Keyframe Animations | 701-1095 | Animation definitions |

---

## CSS STYLING SECTIONS (Detailed)

### Primary Style Blocks

1. **Lines 17-51**: Root CSS variables and body styling
2. **Lines 54-75**: Header section gradient background
3. **Lines 78-112**: Stat box styling (stock/masuk/keluar)
4. **Lines 114-195**: Product card styling with hover effects
5. **Lines 197-380**: Card body, modal, and button styles
6. **Lines 432-542**: Mobile/tablet responsive breakpoints
7. **Lines 554-572**: QR Scanner modal styling
8. **Lines 574-700**: Scanner container and canvas styles
9. **Lines 793-871**: Cart controls and quantity selector
10. **Lines 890-906**: Cart summary display
11. **Lines 1048-1095**: Keyframe animations (spin, slideIn, etc.)

---

## HTML BODY - MAIN CONTENT

### Master Container
- **Lines 1098-1100**: Body tag and container-fluid wrapper opens

### 1. HEADER SECTION
- **Lines 1104-1171**: Header with title and action buttons
  - Line 1105: `<div class="header-section p-4">` opens
  - Lines 1108-1121: Title and description
  - Lines 1124-1170: Button group
    - AI Analysis button (line 1126)
    - Return button (line 1133)
    - Pesanan Shopee button (line 1138)
    - Import DB button (line 1143)
    - Cart button with badge (line 1151)
  - Line 1171: Header closes

### 2. MAIN CARD BODY WRAPPER
- **Lines 1173-1174**: `<div class="card-body p-4">` opens

### 3. SEARCH & FILTER FORM
- **Lines 1176-1418**: Form with search and filters
  - **Lines 1179-1198**: Search input row (col-md-6)
    - Line 1181: Search input field
    - Line 1196: Hidden file reader div for scanner
  - **Lines 1200-1401**: Filter group section (col-md-3)
    - Line 1206: Group search input
    - Lines 1227-1270: Dropdown with groups (@foreach loop)
    - Lines 1271-1401: JavaScript for dropdown functionality
  - **Lines 1405-1418**: Placeholder column (col-md-3) for other filters

### 4. SORTING BUTTONS
- **Lines 1419-1453**: Sort buttons container
  - Line 1420: `<div class="d-flex gap-2 flex-wrap">` opens
  - Lines 1422-1453: Sort buttons
    - "Stock Terendah" (line 1425)
    - "Stock Tertinggi" (line 1430)
    - "A-Z" (line 1435)
    - "Z-A" (line 1449)

### 5. QR/BARCODE SCANNER MODAL
- **Lines 1454-1475**: Scanner modal wrapper (hidden by default)
  - Line 1456: Scanner container div opens
  - Lines 1461-1466: Scanner header with close button
  - Lines 1468-1470: Canvas for video stream
  - Lines 1472-1474: Scanner footer with status indicator

### 6. PRODUCT GRID
- **Lines 1476-1610**: Product grid and pagination
  - Line 1477: `<div class="row g-4">` opens (product grid)
  - **Lines 1480-1595**: @forelse loop for products
    - Lines 1480-1593: Individual product card structure
      - Line 1484: Product card header (with copy button)
      - Lines 1488-1500: Product name and image area
      - Lines 1501-1550: Product information
        - Price badge (line 1502)
        - Stock/Masuk/Keluar indicators (lines 1512-1549)
      - Lines 1551-1560: Quantity selector
      - Lines 1561-1595: Add to cart buttons (pcs and box)
  - Line 1598: Pagination links

### 7. PRODUCT DETAIL MODALS (Dynamic)
- **Lines 1611-1912**: @foreach loop generating modal for each product
  - **Per Product Modal Structure:**
    - Line 1613: `<div class="modal fade" id="productModal{{ $loop->index }}">`
    - Lines 1620-1634: Modal header with product name
    - **Lines 1638-1909**: Modal body with tabs
      - **Lines 1640-1653**: Tab navigation
        - Detail Produk tab (line 1643)
        - Riwayat Stok Masuk tab (line 1649)
      - **Lines 1654-1875**: Tab content
        - **Detail Tab (Lines 1656-1874):**
          - Product details table (lines 1660-1807)
          - Pricing strata table (lines 1809-1875)
        - **History Tab (Lines 1878-1909):**
          - Dynamic inventory history section
          - Loaded via JavaScript (line 3149)
    - Line 1911: Modal closing tags

### 8. CART MODAL
- **Lines 1914-1963**: Shopping cart modal
  - Line 1915: `<div class="modal fade" id="cartModal">`
  - Lines 1919-1927: Modal header
  - **Lines 1929-1961**: Modal body
    - Line 1930: Cart items container (dynamically populated)
    - Lines 1938-1960: Cart summary section
      - Total items (line 1940)
      - Total quantity (line 1944)
      - Action buttons (lines 1949-1960)
        - Copy cart list button
        - Clear cart button

### 9. Document Closing
- **Lines 3362-3364**: Body and HTML closing tags

---

## JAVASCRIPT FUNCTIONS

### Location: Lines 1965-3364 (Main Script Block)

#### INITIALIZATION & STATE MANAGEMENT

| Function | Line | Purpose |
|----------|------|---------|
| `cart` object | 1968 | localStorage cart data |
| `cartReturns` object | 1969 | Returns data per group |

#### CART MANAGEMENT FUNCTIONS

| Function | Line | Purpose |
|----------|------|---------|
| `addToCart()` | 1971 | Add product to cart with quantity |
| `updateCartBadge()` | 2013 | Update cart count badge display |
| `openCartModal()` | 2025 | Show/open cart modal |
| `renderCartItems()` | 2031 | Render cart items grouped by product group |
| `loadReturnsForGroupDisplay()` | 2109 | Async load returns data for cart display |
| `removeFromCart()` | 2149 | Remove single item from cart |
| `clearCart()` | 2156 | Clear entire cart |
| `getReturnsForGroup()` | 2166 | Async fetch returns for group (API call) |
| `copyCartList()` | 2201 | Copy formatted cart to clipboard |

#### UTILITY & VALIDATION FUNCTIONS

| Function | Line | Purpose |
|----------|------|---------|
| `number_format()` | 2333 | Format numbers with thousand separators |
| `showAddToCartNotification()` | 2337 | Show toast notification on add-to-cart |
| `increaseQty()` | 2375 | Increment quantity in product card |
| `decreaseQty()` | 2384 | Decrement quantity in product card |
| `validateQty()` | 2392 | Validate quantity input (non-negative) |
| `copyProductName()` | 2404 | Copy product name to clipboard with format |
| `showCopyNotification()` | 2434 | Show toast notification for copy action |

#### SCANNER STATE & INITIALIZATION

| Variable | Line | Purpose |
|----------|------|---------|
| `scannerState` object | 2486 | Global scanner state tracking |
| `isRunning` | 2487 | Scanner running flag |
| `lastScannedTime` | 2488 | Debounce tracking |
| `debounceTime` | 2489 | 1500ms debounce interval |

#### BARCODE SCANNER FUNCTIONS

| Function | Line | Purpose |
|----------|------|---------|
| `startScanner()` | 2501 | Start QR/barcode scanner UI |
| `startQuaggaEngine()` | 2706 | Initialize Quagga.js barcode detection |
| `onProcessed()` | 2742 | Quagga frame processing callback |
| `onScanSuccess()` | 2835 | Handle successful barcode scan |
| `searchProductByBarcode()` | 2912 | API call to find product by barcode |
| `showProductModal()` | 2943 | Dynamically create and show product modal |
| `updateScannerStatus()` | 3067 | Update scanner status message |
| `stopScanner()` | 3083 | Stop scanner and close scanner modal |

#### MODAL & UI EVENT HANDLERS

| Function | Line | Purpose |
|----------|------|---------|
| `loadInventoryHistoryTab()` | 3149 | Load inventory history via AJAX |
| Document event listeners | 3139+ | Tab switching and modal management |

#### GROUP FILTER JAVASCRIPT

| Section | Lines | Purpose |
|---------|-------|---------|
| Group dropdown logic | 1285-1401 | Search, filter, and select product groups |
| Show dropdown | 1304-1306 | Display group options |
| Filter groups | 1319-1335 | Filter visible groups by search text |
| Select group | 1336-1350 | Handle group selection |
| Hidden input update | 1351-1360 | Update hidden form field |
| Form submission | 1370-1375 | Submit form on selection |

---

## COMPONENT EXTRACTION GUIDE

### 1. HEADER COMPONENT
- **File Location**: [Lines 1104-1171](product.blade.php#L1104-L1171)
- **Type**: Blade view section
- **Dependencies**: Bootstrap icons, custom CSS (lines 54-75)
- **Contains**: Title, 5 action buttons, cart badge
- **Extraction**: Extract to `components/Header.blade.php`

### 2. SEARCH & FILTER FORM
- **File Location**: [Lines 1176-1418](product.blade.php#L1176-L1418)
- **Type**: Form component with dropdown logic
- **Dependencies**: 
  - JavaScript group filter (lines 1285-1401)
  - CSS for search input and dropdown (lines 817-906)
- **Contains**: Search input, group filter dropdown, sort buttons
- **Extraction**: Extract to `components/SearchFilterForm.blade.php`
- **Note**: Move dropdown JavaScript to separate file or Alpine.js component

### 3. PRODUCT CARD
- **File Location**: [Lines 1480-1595](product.blade.php#L1480-L1595)
- **Type**: Looped component (within @forelse)
- **Dependencies**:
  - CSS: product-card styles (lines 114-195)
  - JS: addToCart() function (line 1971)
  - JS: copyProductName() function (line 2404)
  - JS: Quantity validation (lines 2375-2404)
- **Contains**: Image, name, price, stock indicators, quantity selector, add-to-cart buttons
- **Extraction**: Extract to `components/ProductCard.blade.php`

### 4. PRODUCT DETAIL MODAL
- **File Location**: [Lines 1612-1912](product.blade.php#L1612-L1912)
- **Type**: Looped modal component (within @foreach)
- **Dependencies**:
  - CSS: Modal styles (lines 361-380)
  - JS: Tab switching (line 3139)
  - JS: loadInventoryHistoryTab() (line 3149)
  - API endpoint: `/api/products/{id}/inventory-history`
- **Contains**: 2 tabs (Detail, History), product info table, pricing strata
- **Extraction**: Extract to `components/ProductDetailModal.blade.php`

### 5. SCANNER MODAL
- **File Location**: [Lines 1454-1475](product.blade.php#L1454-L1475)
- **Type**: UI modal (hidden by default, shown via JS)
- **Dependencies**:
  - CSS: scanner-modal styles (lines 554-700)
  - JS: startScanner() (line 2501)
  - JS: stopScanner() (line 3083)
  - External library: Quagga.js, html5-qrcode
- **Contains**: Video canvas, status indicator, close button
- **Extraction**: Extract to `components/ScannerModal.blade.php`

### 6. CART MODAL
- **File Location**: [Lines 1914-1963](product.blade.php#L1914-L1963)
- **Type**: Modal component (shown via JS)
- **Dependencies**:
  - CSS: cart-modal styles (lines 807-906)
  - JS: openCartModal() (line 2025)
  - JS: renderCartItems() (line 2031)
  - JS: removeFromCart() (line 2149)
  - JS: clearCart() (line 2156)
  - JS: copyCartList() (line 2201)
- **Contains**: Cart items list, cart summary, action buttons
- **Extraction**: Extract to `components/CartModal.blade.php`

### 7. CART MANAGEMENT (JavaScript Library)
- **File Location**: [Lines 1965-2333](product.blade.php#L1965-L2333)
- **Type**: JavaScript utility library
- **Functions**: 
  - addToCart, updateCartBadge, openCartModal
  - renderCartItems, removeFromCart, clearCart
  - copyCartList, loadReturnsForGroupDisplay
  - number_format, showAddToCartNotification
  - validateQty, copyProductName
- **Extraction**: Extract to `js/cart-management.js`

### 8. SCANNER MANAGEMENT (JavaScript Library)
- **File Location**: [Lines 2486-3140](product.blade.php#L2486-L3140)
- **Type**: JavaScript utility library
- **Functions**:
  - startScanner, startQuaggaEngine, onProcessed
  - onScanSuccess, searchProductByBarcode
  - showProductModal, updateScannerStatus
  - stopScanner, loadInventoryHistoryTab
- **Dependencies**: 
  - Quagga.js library (line 14)
  - html5-qrcode library (line 15)
  - API endpoint: `/api/search-product-by-barcode`
  - API endpoint: `/api/products/{id}/inventory-history`
- **Extraction**: Extract to `js/scanner-management.js`

### 9. GROUP FILTER (JavaScript)
- **File Location**: [Lines 1285-1401](product.blade.php#L1285-L1401)
- **Type**: JavaScript event handlers
- **Functions**: Group dropdown search, filter, selection
- **Extraction**: Extract to `js/group-filter.js` or Alpine.js component

### 10. STYLING (CSS)
- **File Location**: [Lines 17-1095](product.blade.php#L17-L1095)
- **Type**: Internal stylesheet
- **Subsections**: See CSS Styling Sections above
- **Extraction**: Extract to `css/product-page.css`

---

## EXTRACTION WORKFLOW SUMMARY

### Phase 1: Static Components
1. Extract Header (lines 1104-1171) → `components/Header.blade.php`
2. Extract Product Card (lines 1480-1595) → `components/ProductCard.blade.php`
3. Extract Product Detail Modal (lines 1612-1912) → `components/ProductDetailModal.blade.php`
4. Extract Cart Modal (lines 1914-1963) → `components/CartModal.blade.php`

### Phase 2: Dynamic Components
5. Extract Scanner Modal (lines 1454-1475) → `components/ScannerModal.blade.php`
6. Extract Search & Filter Form (lines 1176-1418) → `components/SearchFilterForm.blade.php`

### Phase 3: JavaScript
7. Extract Cart Management (lines 1965-2333) → `js/cart-management.js`
8. Extract Scanner Management (lines 2486-3140) → `js/scanner-management.js`
9. Extract Group Filter (lines 1285-1401) → `js/group-filter.js`

### Phase 4: Styling
10. Extract CSS (lines 17-1095) → `css/product-page.css`

### Phase 5: Integration
11. Update main view to import components
12. Update HTML to reference external JS/CSS files
13. Test all functionality

---

## KEY STATISTICS

| Metric | Count/Lines |
|--------|------------|
| Total File Lines | 3364 |
| CSS Lines | 1079 (lines 17-1095) |
| HTML Lines (Body) | 1804 (lines 1098-2902) |
| JavaScript Lines | 1462 (lines 1965-3364 + inline JS) |
| Product Cards Per Page | Dynamic (@forelse loop) |
| Modals Per Product | 1 detail modal |
| Total Distinct Functions | 18+ JavaScript functions |
| External CSS Libraries | 3 (Bootstrap, FontAwesome, Google Fonts) |
| External JS Libraries | 2 (Quagga.js, html5-qrcode) |
| API Endpoints Used | 2 (`/api/search-product-by-barcode`, `/api/products/{id}/inventory-history`) |

---

## NOTES FOR DEVELOPERS

1. **localStorage**: Cart data is stored in `orderCart` key in localStorage
2. **Responsive Design**: Breakpoints at 432-542 lines for mobile/tablet
3. **Debouncing**: Scanner uses 1500ms debounce to prevent duplicate scans
4. **Dynamic Modals**: Product modals are generated per item in loop
5. **API Integration**: Requires backend endpoints for barcode search and inventory history
6. **Accessibility**: Copy buttons provide visual feedback with animations
7. **Browser APIs**: Uses Vibration API for haptic feedback on scan
8. **Tab System**: Bootstrap tabs for modal tabs (Detail, History)

