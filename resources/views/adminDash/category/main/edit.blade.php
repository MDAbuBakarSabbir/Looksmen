@extends('layouts.Backend.master')
@section('title')
    EDIT MAIN CATEGORY
@endsection
@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm" style="border-radius: 12px; overflow: hidden;">
                <div class="card-header bg-white border-bottom border-light p-4 d-flex align-items-center justify-content-between">
                    <h4 class="mb-0 font-weight-bold" style="color: #1f2937;"><i class="fa-solid fa-pen-to-square mr-2 text-primary"></i>Edit Category: {{ $category->name }}</h4>
                    <a class="btn btn-secondary btn-sm" href="{{ route('category.index') }}" style="border-radius: 8px; font-weight: 600; padding: 8px 16px;"><i class="fa-solid fa-arrow-left mr-2"></i>Back</a>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('category.update', $category->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="form-group mb-3">
                            <label class="form-label font-weight-bold text-muted">Category Name<span class="text-danger">*</span></label>
                            <input type="text" name="category_name" class="form-control" placeholder="Enter Category Name" value="{{ $category->name }}" required style="border-radius: 8px; padding: 10px 14px;">
                        </div>

                        <div class="form-group mb-3">
                            <label class="form-label font-weight-bold text-muted">Category Type<span class="text-danger">*</span></label>
                            <select class="form-control form-select" name="type" required style="border-radius: 8px; height: auto; padding: 10px 14px;">
                                <option value="physical" {{ $category->type == 'physical' ? 'selected' : '' }}>Physical</option>
                                <option value="digital" {{ $category->type == 'digital' ? 'selected' : '' }}>Digital</option>
                            </select>
                        </div>

                        <div class="form-group mb-3">
                            <label class="form-label font-weight-bold text-muted">Commission Rate (%)<span class="text-danger">*</span></label>
                            <input type="number" step="0.01" name="commission_rate" class="form-control" placeholder="Enter Commission Rate" value="{{ $category->commission_rate }}" required style="border-radius: 8px; padding: 10px 14px;">
                        </div>

                        <div class="form-group mb-3">
                            <label class="form-label font-weight-bold text-muted">Free Delivery Minimum Quantity <small class="text-info font-weight-bold">(ঐচ্ছিক - একই ক্যাটাগরির ন্যূনতম কতটি আইটেম কিনলে ফ্রি ডেলিভারি হবে, যেমন: 2, 3 বা 4)</small></label>
                            <input type="number" min="1" name="free_delivery_qty" class="form-control" placeholder="e.g. 2, 3 or 4 (মিনিমাম ২ টি থেকে কার্যকর, খালি রাখলে অফার বন্ধ থাকবে)" value="{{ $category->free_delivery_qty }}" style="border-radius: 8px; padding: 10px 14px;">
                        </div>

                        <!-- Redesigned Icon Field -->
                        <div class="form-group mb-3">
                            <label class="form-label font-weight-bold text-muted">Category Icon Class<span class="text-danger">*</span></label>
                            <div class="input-group mb-2" style="display: flex;">
                                <span class="input-group-text bg-light border-custom" id="icon-preview-container" style="border-radius: 8px 0 0 8px; border: 1px solid #cbd5e1; border-right: none; min-width: 46px; display: flex; align-items: center; justify-content: center;">
                                    <i id="edit-icon-preview" class="{{ $category->icon ?? 'fa-solid fa-icons' }}" style="font-size: 18px; color: #4f46e5;"></i>
                                </span>
                                <input type="text" id="edit-icon-input" name="icon" class="form-control" placeholder="e.g. fa-solid fa-shirt" value="{{ $category->icon }}" required style="border-radius: 0 8px 8px 0; border: 1px solid #cbd5e1; flex: 1; padding: 10px 14px; height: auto;">
                            </div>

                            <!-- Icon Selector Grid -->
                            <div class="icon-selector-wrapper p-3 border rounded mb-3" style="background-color: #f8fafc; max-height: 200px; overflow-y: auto; border: 1px solid #e2e8f0; border-radius: 8px;">
                                <label class="small font-weight-bold text-muted d-block mb-2">Search or select popular icon:</label>
                                <input type="text" id="edit-icon-search" class="form-control form-control-sm mb-3" placeholder="Type to filter..." style="border-radius: 6px; border: 1px solid #cbd5e1; font-size: 13px; padding: 6px 12px; height: auto;">
                                <div class="d-flex flex-wrap" id="edit-icon-grid" style="gap: 8px;"></div>
                            </div>
                        </div>

                        <!-- Redesigned Image Upload & Preview -->
                        <div class="form-group mb-4">
                            <label class="form-label font-weight-bold text-muted">Category Banner Image</label>
                            <div class="image-upload-wrapper border rounded p-3 text-center mb-3" style="border: 2px dashed #cbd5e1; background-color: #f8fafc; border-radius: 12px; cursor: pointer; position: relative; min-height: 120px; display: flex; align-items: center; justify-content: center;">
                                <input type="file" id="edit-image-input" name="image" class="form-control" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; opacity: 0; cursor: pointer; z-index: 2;">
                                <div class="image-upload-placeholder {{ $category->banner ? 'd-none' : '' }}" id="edit-image-placeholder">
                                    <i class="fa-regular fa-image text-muted mb-2" style="font-size: 32px; display: block; margin: 0 auto;"></i>
                                    <p class="mb-1 text-sm font-weight-bold" style="color: #475569;">Click or Drag & Drop Banner Image here</p>
                                    <span class="text-xs text-muted">Supports JPG, PNG, WEBP</span>
                                </div>
                                <div class="image-upload-preview {{ $category->banner ? '' : 'd-none' }}" id="edit-image-preview-container" style="position: relative; z-index: 3; width: 100%;">
                                    <img id="edit-image-preview" src="{{ $category->banner ? asset('Uploads/' . $category->banner) : '' }}" alt="Preview" style="max-height: 120px; max-width: 100%; border-radius: 8px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);">
                                    <div class="mt-2">
                                        <button type="button" id="edit-image-remove" class="btn btn-xs btn-danger" style="border-radius: 6px; padding: 4px 10px; font-size: 11px;"><i class="fa fa-times mr-1"></i> Remove / Reset Image</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            <label class="form-label font-weight-bold text-muted">Meta Title (Optional)</label>
                            <input type="text" name="meta_title" class="form-control" placeholder="SEO Title" value="{{ $category->meta_title }}" style="border-radius: 8px; padding: 10px 14px;">
                        </div>

                        <div class="form-group mb-4">
                            <label class="form-label font-weight-bold text-muted">Meta Description (Optional)</label>
                            <textarea name="meta_description" class="form-control" rows="3" placeholder="SEO Description" style="border-radius: 8px; padding: 10px 14px;">{{ $category->meta_descritption }}</textarea>
                        </div>

                        <button type="submit" class="btn btn-primary btn-block" style="border-radius: 8px; font-weight: 600; padding: 12px; background: linear-gradient(135deg, #4f46e5, #6366f1); border: none;">
                            <i class="fa-solid fa-cloud-arrow-up mr-2"></i>Update Category
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('style')
    <style>
        /* Premium Icon Selector styling */
        .icon-selector-item {
            width: 42px;
            height: 42px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            border: 1px solid #e2e8f0;
            background-color: #ffffff;
            cursor: pointer;
            font-size: 16px;
            color: #475569;
            transition: all 0.15s ease;
        }
        .icon-selector-item:hover {
            border-color: #4f46e5;
            color: #4f46e5;
            background-color: #f5f3ff;
            transform: translateY(-2px);
        }
        .icon-selector-item.selected {
            background-color: #4f46e5;
            border-color: #4f46e5;
            color: #ffffff;
            box-shadow: 0 4px 6px -1px rgba(79, 70, 229, 0.25);
        }
    </style>
@endsection

@section('script')
    <script>
        // Wrap in DOMContentLoaded to ensure elements are parsed before setup runs
        document.addEventListener('DOMContentLoaded', function() {
            // Popular icons list (Free Font Awesome Solid Icons)
            const popularIcons = [
                // Clothing & Fashion
                { class: 'fa-solid fa-shirt', tags: 'shirt clothing apparel top dress tshirt fashion wear' },
                { class: 'fa-solid fa-bag-shopping', tags: 'bag shopping handbag accessories purse case pocket cart' },
                { class: 'fa-solid fa-shoe-prints', tags: 'shoe boot prints walk sneaker feet footwear' },
                { class: 'fa-solid fa-glasses', tags: 'glasses eye sun spectacles fashion vision' },
                { class: 'fa-solid fa-hat-cowboy', tags: 'hat cowboy fashion cap headwear cap helmet' },
                { class: 'fa-solid fa-gem', tags: 'gem jewel jewelry diamond stone ring luxury gold' },
                { class: 'fa-solid fa-clock', tags: 'clock watch time timer schedule wristwatch' },
                { class: 'fa-solid fa-socks', tags: 'socks foot clothing fashion' },
                { class: 'fa-solid fa-vest', tags: 'vest clothing fashion jacket' },
                { class: 'fa-solid fa-crown', tags: 'crown king queen royal gold jewelry' },
                
                // Electronics, Devices & Gadgets
                { class: 'fa-solid fa-laptop', tags: 'laptop computer electronics monitor dev screen tech pc' },
                { class: 'fa-solid fa-mobile-screen-button', tags: 'mobile phone phone screens cellular smart mobile device tech' },
                { class: 'fa-solid fa-headphones', tags: 'headphones sound music audio listen speaker gadget headset' },
                { class: 'fa-solid fa-tv', tags: 'tv television screen display monitor video television' },
                { class: 'fa-solid fa-camera', tags: 'camera photo picture video lens capture photography' },
                { class: 'fa-solid fa-gamepad', tags: 'gamepad game controller console playstation xbox play joystick gaming' },
                { class: 'fa-solid fa-plug', tags: 'plug cable electricity charge power wire' },
                { class: 'fa-solid fa-battery-three-quarters', tags: 'battery power charge energy' },
                { class: 'fa-solid fa-print', tags: 'print printer ink paper office hardcopy' },
                { class: 'fa-solid fa-keyboard', tags: 'keyboard typing board input tech key' },
                { class: 'fa-solid fa-mouse', tags: 'mouse click pointer input tech' },
                { class: 'fa-solid fa-desktop', tags: 'desktop monitor screen pc computer' },
                { class: 'fa-solid fa-tablet-screen-button', tags: 'tablet ipad screen mobile device' },
                { class: 'fa-solid fa-sim-card', tags: 'sim card memory micro chip' },
                { class: 'fa-solid fa-wifi', tags: 'wifi internet signal connect network' },
                
                // Home, Kitchen & Living
                { class: 'fa-solid fa-house', tags: 'house home building apartment living address residency' },
                { class: 'fa-solid fa-couch', tags: 'couch sofa furniture lounge home seat chair sofa' },
                { class: 'fa-solid fa-bed', tags: 'bed sleep hotel room furniture rest' },
                { class: 'fa-solid fa-bath', tags: 'bath shower tub bathroom wash clean restroom' },
                { class: 'fa-solid fa-faucet', tags: 'faucet water tap sink plumbing' },
                { class: 'fa-solid fa-lightbulb', tags: 'lightbulb light lamp idea brainstorm bright electricity' },
                { class: 'fa-solid fa-soap', tags: 'soap bubble clean hygiene wash liquid' },
                { class: 'fa-solid fa-door-open', tags: 'door open entry gate exit home' },
                { class: 'fa-solid fa-fan', tags: 'fan wind cool air ventilation machine' },
                { class: 'fa-solid fa-key', tags: 'key lock secret security login password access' },
                { class: 'fa-solid fa-lock', tags: 'lock key security protect private safety pad' },
                
                // Grocery, Food & Beverage
                { class: 'fa-solid fa-basket-shopping', tags: 'basket shopping grocery food cart shop market supermarket container' },
                { class: 'fa-solid fa-utensils', tags: 'utensils food restaurant kitchen cooking spoon fork plate diner' },
                { class: 'fa-solid fa-bowl-food', tags: 'bowl food meal rice soup kitchen breakfast lunch dinner' },
                { class: 'fa-solid fa-cake-candles', tags: 'cake candles birthday sweet dessert bake party celebration' },
                { class: 'fa-solid fa-cookie-bite', tags: 'cookie bite sweet biscuit bakery food snack' },
                { class: 'fa-solid fa-apple-whole', tags: 'apple whole fruit healthy food agriculture fresh nature red' },
                { class: 'fa-solid fa-carrot', tags: 'carrot vegetable healthy food kitchen orange veggie' },
                { class: 'fa-solid fa-fish', tags: 'fish seafood animal ocean cooking raw water' },
                { class: 'fa-solid fa-mug-hot', tags: 'mug hot coffee tea drink cafe beverage espresso milk' },
                { class: 'fa-solid fa-wine-glass', tags: 'wine glass drink bar alcohol beverage party liquid' },
                { class: 'fa-solid fa-bottle-water', tags: 'bottle water drink beverage container mineral plastic' },
                { class: 'fa-solid fa-ice-cream', tags: 'ice cream sweet dessert cold summer cone' },
                { class: 'fa-solid fa-burger', tags: 'burger fast food hamburger cheese kitchen snack bread' },
                { class: 'fa-solid fa-pizza-slice', tags: 'pizza slice cheese fast food snack kitchen italian' },
                { class: 'fa-solid fa-egg', tags: 'egg food breakfast chicken egg protein' },
                { class: 'fa-solid fa-cheese', tags: 'cheese dairy yellow food snack' },
                { class: 'fa-solid fa-pepper-hot', tags: 'pepper chili spicy hot food vegetable spice' },
                
                // Health, Pharmacy, Beauty & Personal Care
                { class: 'fa-solid fa-heart', tags: 'heart health medical love care safety life red' },
                { class: 'fa-solid fa-stethoscope', tags: 'stethoscope doctor medical clinic hospital health nurse' },
                { class: 'fa-solid fa-briefcase-medical', tags: 'briefcase medical aid box kit firstaid rescue' },
                { class: 'fa-solid fa-pills', tags: 'pills drug medicine tablet health pharmacy pharmacy care' },
                { class: 'fa-solid fa-wand-magic-sparkles', tags: 'wand magic beauty cosmetic sparkle makeup lipstick blush' },
                { class: 'fa-solid fa-comb', tags: 'comb hair barber salon style grooming comb' },
                { class: 'fa-solid fa-spray-can-sparkles', tags: 'spray can perfume aerosol cosmetic fragrance' },
                { class: 'fa-solid fa-tooth', tags: 'tooth dental dentist health hygiene clean mouth' },
                { class: 'fa-solid fa-wheelchair', tags: 'wheelchair access patient medical health sign' },
                
                // Baby & Kids
                { class: 'fa-solid fa-baby-carriage', tags: 'baby carriage stroller kid child parenting infant toddler' },
                { class: 'fa-solid fa-child', tags: 'child kid person human family play' },
                { class: 'fa-solid fa-palette', tags: 'palette paint color art draw creative canvas artist painting craft' },
                { class: 'fa-solid fa-puzzle-piece', tags: 'puzzle piece game toy child thinking match' },
                
                // Sports, Toys & Hobbies
                { class: 'fa-solid fa-soccer-ball', tags: 'soccer ball football sport athletic game play sphere' },
                { class: 'fa-solid fa-basketball', tags: 'basketball ball sport play athletic orange hoop' },
                { class: 'fa-solid fa-dumbbell', tags: 'dumbbell gym fitness workout weight muscle train lift' },
                { class: 'fa-solid fa-bicycle', tags: 'bicycle bike wheel ride cycling travel exercise' },
                { class: 'fa-solid fa-music', tags: 'music song note sound instrument melody tune sound' },
                { class: 'fa-solid fa-guitar', tags: 'guitar music instrument audio play rock acoustic string' },
                { class: 'fa-solid fa-book-open', tags: 'book open read study novel library paper text literature' },
                { class: 'fa-solid fa-compass', tags: 'compass direction travel map navigate north find route' },
                { class: 'fa-solid fa-trophy', tags: 'trophy win award prize gold achieve champion' },
                { class: 'fa-solid fa-medal', tags: 'medal award win place prize ribbon' },
                
                // Vehicles & Transport
                { class: 'fa-solid fa-car', tags: 'car auto automobile motor vehicle travel drive ride' },
                { class: 'fa-solid fa-truck', tags: 'truck vehicle transport delivery shipping logistics cargo van freight' },
                { class: 'fa-solid fa-motorcycle', tags: 'motorcycle bike speed vehicle ride motor' },
                { class: 'fa-solid fa-plane', tags: 'plane airplane flight airport travel fly journey sky' },
                { class: 'fa-solid fa-ship', tags: 'ship boat water cruise transport travel ocean ferry' },
                { class: 'fa-solid fa-helicopter', tags: 'helicopter fly vehicle transport air chopper' },
                { class: 'fa-solid fa-bus', tags: 'bus coach vehicle travel transport school group' },
                { class: 'fa-solid fa-train', tags: 'train rail vehicle travel transport station' },
                
                // Tools & Hardware
                { class: 'fa-solid fa-wrench', tags: 'wrench tool repair hardware construct fix hammer toolbox utility' },
                { class: 'fa-solid fa-hammer', tags: 'hammer tool construction build hardware forge tool' },
                { class: 'fa-solid fa-screwdriver', tags: 'screwdriver tool repair hardware fix screwdriver' },
                { class: 'fa-solid fa-scissors', tags: 'scissors cut paper salon tailor tool office blade' },
                { class: 'fa-solid fa-box', tags: 'box package storage delivery container carton package post' },
                { class: 'fa-solid fa-toolbox', tags: 'toolbox tools storage hardware kit repair' },
                { class: 'fa-solid fa-shield-halved', tags: 'shield security protect guard safety defense armor' },
                
                // Office & Finance
                { class: 'fa-solid fa-briefcase', tags: 'briefcase work job business office bag portfolio bag' },
                { class: 'fa-solid fa-envelope', tags: 'envelope mail letter post message inbox receive send' },
                { class: 'fa-solid fa-paperclip', tags: 'paperclip clip attach file document office office link' },
                { class: 'fa-solid fa-credit-card', tags: 'credit card payment money bank finance purchase plastic' },
                { class: 'fa-solid fa-wallet', tags: 'wallet money finance cash pay card pocket cash' },
                { class: 'fa-solid fa-coins', tags: 'coins money gold cash wealth finance change gold' },
                { class: 'fa-solid fa-dollar-sign', tags: 'dollar sign money finance cash currency usd exchange' },
                { class: 'fa-solid fa-chart-line', tags: 'chart line graph growth finance business statistics up' },
                { class: 'fa-solid fa-calculator', tags: 'calculator math compute numbers office finance math' },
                { class: 'fa-solid fa-piggy-bank', tags: 'piggy bank money savings gold finance invest' },
                
                // Animals, Plants & Nature
                { class: 'fa-solid fa-paw', tags: 'paw pet animal dog cat vet footprint tracks' },
                { class: 'fa-solid fa-dog', tags: 'dog pet animal canine friend doggy' },
                { class: 'fa-solid fa-cat', tags: 'cat pet animal feline meow kitten' },
                { class: 'fa-solid fa-leaf', tags: 'leaf plant nature green tree organic bio environmental' },
                { class: 'fa-solid fa-tree', tags: 'tree nature forest wood green pine environmental' },
                { class: 'fa-solid fa-seedling', tags: 'seedling plant grow agriculture nature sprout soil farming' },
                { class: 'fa-solid fa-umbrella', tags: 'umbrella rain weather protection shade sun weather' },
                { class: 'fa-solid fa-snowflake', tags: 'snowflake ice cold winter snow weather freeze' },
                { class: 'fa-solid fa-sun', tags: 'sun summer weather bright warm day light shine' },
                { class: 'fa-solid fa-cloud', tags: 'cloud weather sky rain cloudy overcast' },
                { class: 'fa-solid fa-fire', tags: 'fire hot flame burn cook heat energy' },
                { class: 'fa-solid fa-bolt', tags: 'bolt lightning flash power energy screen storm spark' },
                
                // Miscellaneous, UI & Symbols
                { class: 'fa-solid fa-user', tags: 'user person member account profile human avatar' },
                { class: 'fa-solid fa-users', tags: 'users group people family team audience gathering social' },
                { class: 'fa-solid fa-star', tags: 'star rate favorite gold like bookmark quality review' },
                { class: 'fa-solid fa-thumbs-up', tags: 'thumbsup like good positive approve agree click' },
                { class: 'fa-solid fa-bell', tags: 'bell alert notify ring alarm sound push' },
                { class: 'fa-solid fa-map-pin', tags: 'map pin location gps address marker navigate locate destination' },
                { class: 'fa-solid fa-search', tags: 'search find zoom spy glass look detect zoom glass' },
                { class: 'fa-solid fa-share-nodes', tags: 'share send network connect social share nodes' },
                { class: 'fa-solid fa-trash-can', tags: 'trash can delete bin discard waste empty' },
                { class: 'fa-solid fa-flag', tags: 'flag nation mark indicator coordinate nation banner' },
                { class: 'fa-solid fa-heart-pulse', tags: 'heart pulse heartbeat medical ecg hospital' },
                { class: 'fa-solid fa-circle-info', tags: 'info circle details description text' },
                { class: 'fa-solid fa-circle-question', tags: 'question query help circle support faq' },
                { class: 'fa-solid fa-circle-check', tags: 'check circle correct pass success yes right' },
                { class: 'fa-solid fa-circle-xmark', tags: 'xmark circle cancel incorrect error fail cross no' }
            ];

            // 1. Image Preview Logic for Category Edit
            const editImageInput = document.getElementById('edit-image-input');
            const editImagePlaceholder = document.getElementById('edit-image-placeholder');
            const editImagePreviewContainer = document.getElementById('edit-image-preview-container');
            const editImagePreview = document.getElementById('edit-image-preview');
            const editImageRemove = document.getElementById('edit-image-remove');

            if (editImageInput) {
                editImageInput.addEventListener('change', function(e) {
                    if (e.target.files && e.target.files[0]) {
                        const reader = new FileReader();
                        reader.onload = function(event) {
                            editImagePreview.src = event.target.result;
                            editImagePlaceholder.classList.add('d-none');
                            editImagePreviewContainer.classList.remove('d-none');
                        };
                        reader.readAsDataURL(e.target.files[0]);
                    }
                });
            }

            if (editImageRemove) {
                editImageRemove.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    editImageInput.value = '';
                    editImagePreview.src = '';
                    editImagePreviewContainer.classList.add('d-none');
                    editImagePlaceholder.classList.remove('d-none');
                });
            }

            // 2. Icon Selection Grid & Search Logic for Category Edit
            const editIconGrid = document.getElementById('edit-icon-grid');
            const editIconInput = document.getElementById('edit-icon-input');
            const editIconPreview = document.getElementById('edit-icon-preview');
            const editIconSearch = document.getElementById('edit-icon-search');

            if (editIconGrid && editIconInput && editIconPreview) {
                function renderIconGrid(filter = '') {
                    editIconGrid.innerHTML = '';
                    popularIcons.forEach(icon => {
                        if (filter === '' || icon.class.includes(filter) || icon.tags.includes(filter)) {
                            const iconBtn = document.createElement('div');
                            iconBtn.className = 'icon-selector-item';
                            if (editIconInput.value === icon.class) {
                                iconBtn.classList.add('selected');
                            }
                            iconBtn.innerHTML = `<i class="${icon.class}"></i>`;
                            iconBtn.title = icon.class;
                            
                            iconBtn.addEventListener('click', function() {
                                document.querySelectorAll('#edit-icon-grid .icon-selector-item').forEach(el => el.classList.remove('selected'));
                                this.classList.add('selected');
                                editIconInput.value = icon.class;
                                editIconPreview.className = icon.class;
                            });
                            
                            editIconGrid.appendChild(iconBtn);
                        }
                    });
                }

                renderIconGrid();

                if (editIconSearch) {
                    editIconSearch.addEventListener('input', function() {
                        renderIconGrid(this.value.toLowerCase().trim());
                    });
                }

                editIconInput.addEventListener('input', function() {
                    const val = this.value.trim();
                    editIconPreview.className = val || 'fa-solid fa-icons';
                    
                    document.querySelectorAll('#edit-icon-grid .icon-selector-item').forEach(el => {
                        const iconEl = el.querySelector('i');
                        if (iconEl && iconEl.className === val) {
                            el.classList.add('selected');
                        } else {
                            el.classList.remove('selected');
                        }
                    });
                });
            }
        });
    </script>
@endsection
