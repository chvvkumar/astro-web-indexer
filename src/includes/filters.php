<form id="filters-form" method="get" class="bg-gray-800 p-4 rounded-lg shadow-md flex flex-wrap gap-4 items-end mb-2">
    <input type="hidden" name="dir" value="<?= htmlspecialchars($dir) ?>">
    <input type="hidden" name="page" value="1"> <!-- Resetta la pagina quando si applicano i filtri -->
    <input type="hidden" name="lang" value="<?= htmlspecialchars($lang) ?>">
    <input type="hidden" name="sort_by" value="<?= htmlspecialchars($sortBy) ?>">
    <input type="hidden" name="sort_order" value="<?= htmlspecialchars($sortOrder) ?>">

    <?php $currentParams = $_GET; // Per i filtri interdipendenti ?>

    <div>
                <label for="object-select" class="block text-sm font-medium text-gray-300 mb-1"><?php echo __('object') ?></label>
        <select id="object-select" name="object" class="appearance-none bg-gray-700 border border-gray-600 text-gray-100 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2.5 w-40 pr-8 bg-no-repeat bg-right" style="background-image: url('data:image/svg+xml,%3csvg xmlns=\'http://www.w3.org/2000/svg\' fill=\'none\' viewBox=\'0 0 20 20\'%3e%3cpath stroke=\'%236b7280\' stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'1.5\' d=\'M6 8l4 4 4-4\'/%3e%3c/svg%3e'); background-position: right 0.5rem center; background-size: 1.5em 1.5em;">
            <option value=""><?php echo __('all_objects') ?></option>
            <?php
            // Get OBJECT values considering other filters (except OBJECT itself)
            $availableObjects = getDistinctValues($conn, 'object', $dir, '', $filterFilter, $filterImgtype);
            foreach($availableObjects as $o): ?>
                <option value="<?= htmlspecialchars($o) ?>" <?= $o==$filterObject?'selected':'' ?>><?= htmlspecialchars($o) ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <div>
                <label for="filter-select" class="block text-sm font-medium text-gray-300 mb-1"><?php echo __('filter') ?></label>
        <select id="filter-select" name="filter" class="appearance-none bg-gray-700 border border-gray-600 text-gray-100 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2.5 w-40 pr-8 bg-no-repeat bg-right" style="background-image: url('data:image/svg+xml,%3csvg xmlns=\'http://www.w3.org/2000/svg\' fill=\'none\' viewBox=\'0 0 20 20\'%3e%3cpath stroke=\'%236b7280\' stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'1.5\' d=\'M6 8l4 4 4-4\'/%3e%3c/svg%3e'); background-position: right 0.5rem center; background-size: 1.5em 1.5em;">
            <option value=""><?php echo __('all_filters') ?></option>
            <?php
            // Get FILTER values considering other filters (except FILTER itself)
            $availableFilters = getDistinctValues($conn, 'filter', $dir, $filterObject, '', $filterImgtype);
            foreach($availableFilters as $f): ?>
                <option value="<?= htmlspecialchars($f) ?>" <?= $f==$filterFilter?'selected':'' ?>><?= htmlspecialchars($f) ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <div>
                <label for="imgtype-select" class="block text-sm font-medium text-gray-300 mb-1"><?php echo __('type') ?></label>
        <select id="imgtype-select" name="imgtype" class="appearance-none bg-gray-700 border border-gray-600 text-gray-100 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2.5 w-40 pr-8 bg-no-repeat bg-right" style="background-image: url('data:image/svg+xml,%3csvg xmlns=\'http://www.w3.org/2000/svg\' fill=\'none\' viewBox=\'0 0 20 20\'%3e%3cpath stroke=\'%236b7280\' stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'1.5\' d=\'M6 8l4 4 4-4\'/%3e%3c/svg%3e'); background-position: right 0.5rem center; background-size: 1.5em 1.5em;">
            <option value=""><?php echo __('all_types') ?></option>
            <?php
            // Get IMGTYPE values considering other filters (except IMGTYPE itself)
            $availableImgtypes = getDistinctValues($conn, 'imgtype', $dir, $filterObject, $filterFilter, '');
                        foreach($availableImgtypes as $i): ?>
                <option value="<?= htmlspecialchars($i) ?>" <?= $i==$filterImgtype?'selected':'' ?>><?= htmlspecialchars($i) ?></option>
            <?php endforeach; ?>
        </select>
    </div>

        <!-- Date OBS Filter -->
    <div class="border-l border-gray-600 pl-4">
        <label for="date_obs_from" class="block text-sm font-medium text-gray-300 mb-1"><?php echo __('observation_date'); ?>:</label>
        <div class="flex items-center gap-2">
            <input type="date" id="date_obs_from" name="date_obs_from" value="<?= htmlspecialchars($_GET['date_obs_from'] ?? '') ?>" class="bg-gray-700 border border-gray-600 text-gray-100 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2.5">
            <span class="text-gray-400">-</span>
            <input type="date" id="date_obs_to" name="date_obs_to" value="<?= htmlspecialchars($_GET['date_obs_to'] ?? '') ?>" class="bg-gray-700 border border-gray-600 text-gray-100 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2.5">
                </div>
    </div>

    <!-- Items per page -->
        <div class="border-l border-gray-600 pl-4">
        <label for="per_page-select" class="block text-sm font-medium text-gray-300 mb-1"><?php echo __('elements_per_page') ?>:</label>
        <select id="per_page-select" name="per_page" class="appearance-none bg-gray-700 border border-gray-600 text-gray-100 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2.5 w-24 pr-8 bg-no-repeat bg-right" style="background-image: url('data:image/svg+xml,%3csvg xmlns=\'http://www.w3.org/2000/svg\' fill=\'none\' viewBox=\'0 0 20 20\'%3e%3cpath stroke=\'%236b7280\' stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'1.5\' d=\'M6 8l4 4 4-4\'/%3e%3c/svg%3e'); background-position: right 0.5rem center; background-size: 1.5em 1.5em;">
            <?php foreach(PER_PAGE_OPTIONS as $option): ?>
                <option value="<?= $option ?>" <?= $option==$perPage?'selected':'' ?>><?= $option ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    
<div class="border-l border-gray-600 pl-4 flex items-center gap-4">
    <!-- View Toggle and Size Slider -->
    <div class="flex items-center gap-4 pr-4 border-r border-gray-600">
        <div class="flex items-center bg-gray-700 rounded-lg">
            <button id="list-view-btn" class="flex items-center justify-center p-2 rounded-l-lg bg-blue-600 hover:bg-blue-700 transition-colors" title="<?php echo __('list_view') ?>">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
            <button id="thumbnail-view-btn" class="flex items-center justify-center p-2 rounded-r-lg hover:bg-blue-700 transition-colors" title="<?php echo __('thumbnail_view') ?>">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                </svg>
            </button>
        </div>
        
        <div class="flex flex-col">
            <label for="thumbnail-size-slider" class="text-sm font-medium text-gray-300 mb-1"><?php echo __('thumbnail_size') ?></label>
            <div class="flex items-center gap-2">
                <span class="text-sm text-gray-400">S</span>
                <input type="range" id="thumbnail-size-slider" min="1" max="5" value="3" class="w-24 h-2 bg-gray-700 rounded-lg appearance-none cursor-pointer">
                <span class="text-sm text-gray-400">L</span>
            </div>
        </div>
    </div>
    
    <?php
        // Build a clean URL for the reset button, preserving only dir and lang
        $reset_params = [
            'dir' => $dir,
            'lang' => $lang
        ];
        $reset_href = '?' . http_build_query($reset_params);
    ?>
    <a href="<?= htmlspecialchars($reset_href) ?>" 
       class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg transition duration-200">
        <?php echo __('reset_filters') ?>
    </a>

    <div class="flex items-center">
        <input id="show-advanced-fields" 
               name="show_advanced" 
               type="checkbox" 
               value="1" 
               class="w-4 h-4 text-blue-600 bg-gray-700 border-gray-600 rounded focus:ring-blue-600 ring-offset-gray-800 focus:ring-2"
               <?php if ($showAdvanced) echo 'checked'; ?>>
        <label for="show-advanced-fields" 
               class="ml-2 text-sm font-medium text-gray-300">
            <?php echo __('show_advanced_fields') ?>
        </label>
    </div>
</div>


</form>

<!-- Stretch Settings Controls -->
<div id="stretch-settings-container" class="bg-gray-800 p-4 rounded-lg shadow-md mb-6">
    <div class="flex justify-between items-center mb-4">
        <h3 class="text-lg font-medium text-white"><?php echo __('stretch_settings') ?? 'Stretch Settings' ?></h3>
        <div class="flex items-center gap-2">
            <span class="text-sm text-gray-300"><?php echo __('current_folder') ?? 'Current Folder' ?>: </span>
            <span id="current-folder-path" class="text-sm font-mono bg-gray-700 px-2 py-1 rounded"><?php echo $dir ? '/' . $dir : '/' ?></span>
            <button id="save-stretch-settings" type="button" class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded text-sm">
                <?php echo __('save_settings') ?? 'Save Settings' ?>
            </button>
            <button id="toggle-stretch-settings" type="button" class="bg-gray-600 hover:bg-gray-700 text-white px-3 py-1 rounded text-sm">
                <?php echo __('hide_settings') ?? 'Hide Settings' ?>
            </button>
        </div>
    </div>
    
    <div id="stretch-settings-form" class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Stretch Type Selection -->
        <div class="flex flex-col space-y-3">
            <label class="text-sm font-medium text-gray-300"><?php echo __('stretch_type') ?? 'Stretch Type' ?></label>
            <div class="flex flex-col space-y-2">
                <label class="inline-flex items-center">
                    <input type="radio" name="stretch-type" value="linear" class="text-blue-600" checked>
                    <span class="ml-2 text-gray-300"><?php echo __('linear_stretch') ?? 'Linear Stretch' ?></span>
                </label>
                <label class="inline-flex items-center">
                    <input type="radio" name="stretch-type" value="pixinsight_stf" class="text-blue-600">
                    <span class="ml-2 text-gray-300"><?php echo __('pixinsight_stf') ?? 'PixInsight STF' ?></span>
                </label>
            </div>
            
            <div class="mt-4">
                <label class="inline-flex items-center">
                    <input type="checkbox" id="apply-to-subfolders" class="text-blue-600" checked>
                    <span class="ml-2 text-gray-300"><?php echo __('apply_to_subfolders') ?? 'Apply to subfolders' ?></span>
                </label>
            </div>
            
            <div class="mt-4 text-sm text-gray-400">
                <p class="mb-2"><?php echo __('reindex_note') ?? 'Note: Changes require reindexing to apply to existing thumbnails.' ?></p>
                <button id="reindex-folder-btn" type="button" class="bg-indigo-600 hover:bg-indigo-700 text-white px-3 py-1 rounded text-sm">
                    <?php echo __('reindex_folder') ?? 'Reindex This Folder' ?>
                </button>
            </div>
        </div>
        
        <!-- Stretch Parameters -->
        <div class="space-y-4">
            <!-- Linear Stretch Parameters (shown by default) -->
            <div id="linear-params" class="space-y-4">
                <div>
                    <label for="linear-low" class="block text-sm font-medium text-gray-300 mb-1">
                        <?php echo __('low_percentile') ?? 'Low Percentile' ?> (%)
                    </label>
                    <div class="flex items-center gap-3">
                        <input type="range" id="linear-low" min="0" max="10" step="0.1" value="0.5" class="w-full">
                        <span id="linear-low-value" class="text-gray-300 w-12 text-center">0.5</span>
                    </div>
                </div>
                
                <div>
                    <label for="linear-high" class="block text-sm font-medium text-gray-300 mb-1">
                        <?php echo __('high_percentile') ?? 'High Percentile' ?> (%)
                    </label>
                    <div class="flex items-center gap-3">
                        <input type="range" id="linear-high" min="90" max="100" step="0.1" value="99.5" class="w-full">
                        <span id="linear-high-value" class="text-gray-300 w-12 text-center">99.5</span>
                    </div>
                </div>
            </div>
            
            <!-- PixInsight STF Parameters (hidden initially) -->
            <div id="pixinsight-params" class="space-y-4 hidden">
                <div>
                    <label for="stf-shadow" class="block text-sm font-medium text-gray-300 mb-1">
                        <?php echo __('shadow_clip') ?? 'Shadow Clip' ?>
                    </label>
                    <div class="flex items-center gap-3">
                        <input type="range" id="stf-shadow" min="0" max="0.2" step="0.001" value="0.0" class="w-full">
                        <span id="stf-shadow-value" class="text-gray-300 w-12 text-center">0.0</span>
                    </div>
                </div>
                
                <div>
                    <label for="stf-highlight" class="block text-sm font-medium text-gray-300 mb-1">
                        <?php echo __('highlight_clip') ?? 'Highlight Clip' ?>
                    </label>
                    <div class="flex items-center gap-3">
                        <input type="range" id="stf-highlight" min="0" max="0.2" step="0.001" value="0.0" class="w-full">
                        <span id="stf-highlight-value" class="text-gray-300 w-12 text-center">0.0</span>
                    </div>
                </div>
                
                <div>
                    <label for="stf-midtones" class="block text-sm font-medium text-gray-300 mb-1">
                        <?php echo __('midtones_balance') ?? 'Midtones Balance' ?>
                    </label>
                    <div class="flex items-center gap-3">
                        <input type="range" id="stf-midtones" min="0" max="1" step="0.01" value="0.5" class="w-full">
                        <span id="stf-midtones-value" class="text-gray-300 w-12 text-center">0.5</span>
                    </div>
                </div>
                
                <div>
                    <label for="stf-strength" class="block text-sm font-medium text-gray-300 mb-1">
                        <?php echo __('strength') ?? 'Strength' ?>
                    </label>
                    <div class="flex items-center gap-3">
                        <input type="range" id="stf-strength" min="0.5" max="2" step="0.01" value="1.0" class="w-full">
                        <span id="stf-strength-value" class="text-gray-300 w-12 text-center">1.0</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div id="stretch-settings-status" class="mt-4 text-sm hidden"></div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Get DOM elements
    const stretchTypeRadios = document.querySelectorAll('input[name="stretch-type"]');
    const linearParamsDiv = document.getElementById('linear-params');
    const pixinsightParamsDiv = document.getElementById('pixinsight-params');
    const saveButton = document.getElementById('save-stretch-settings');
    const toggleButton = document.getElementById('toggle-stretch-settings');
    const settingsForm = document.getElementById('stretch-settings-form');
    const statusDiv = document.getElementById('stretch-settings-status');
    const reindexButton = document.getElementById('reindex-folder-btn');
    const applyToSubfolders = document.getElementById('apply-to-subfolders');
    
    // Get all range inputs and value spans
    const rangeInputs = {
        linearLow: { input: document.getElementById('linear-low'), value: document.getElementById('linear-low-value') },
        linearHigh: { input: document.getElementById('linear-high'), value: document.getElementById('linear-high-value') },
        stfShadow: { input: document.getElementById('stf-shadow'), value: document.getElementById('stf-shadow-value') },
        stfHighlight: { input: document.getElementById('stf-highlight'), value: document.getElementById('stf-highlight-value') },
        stfMidtones: { input: document.getElementById('stf-midtones'), value: document.getElementById('stf-midtones-value') },
        stfStrength: { input: document.getElementById('stf-strength'), value: document.getElementById('stf-strength-value') }
    };
    
    // Update value display for all range inputs
    for (const key in rangeInputs) {
        const {input, value} = rangeInputs[key];
        if (input && value) {
            input.addEventListener('input', () => {
                value.textContent = input.value;
            });
        }
    }
    
    // Toggle between linear and pixinsight parameters based on selected type
    stretchTypeRadios.forEach(radio => {
        radio.addEventListener('change', () => {
            if (radio.value === 'linear') {
                linearParamsDiv.classList.remove('hidden');
                pixinsightParamsDiv.classList.add('hidden');
            } else if (radio.value === 'pixinsight_stf') {
                linearParamsDiv.classList.add('hidden');
                pixinsightParamsDiv.classList.remove('hidden');
            }
        });
    });
    
    // Toggle stretch settings visibility
    toggleButton.addEventListener('click', () => {
        if (settingsForm.classList.contains('hidden')) {
            settingsForm.classList.remove('hidden');
            toggleButton.textContent = toggleButton.textContent.replace('Show', 'Hide');
        } else {
            settingsForm.classList.add('hidden');
            toggleButton.textContent = toggleButton.textContent.replace('Hide', 'Show');
        }
    });
    
    // Load current folder settings
    function loadFolderSettings() {
        const currentFolder = document.getElementById('current-folder-path').textContent;
        
        // Show loading status
        statusDiv.textContent = 'Loading settings...';
        statusDiv.className = 'mt-4 text-sm text-blue-400';
        statusDiv.classList.remove('hidden');
        
        fetch(`/api/folder_stretch_settings.php?folder_path=${encodeURIComponent(currentFolder)}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update form with retrieved settings
                    const settings = data.settings;
                    
                    // Set stretch type
                    document.querySelector(`input[name="stretch-type"][value="${settings.stretch_type}"]`).checked = true;
                    
                    // Show/hide appropriate parameter sections
                    if (settings.stretch_type === 'linear') {
                        linearParamsDiv.classList.remove('hidden');
                        pixinsightParamsDiv.classList.add('hidden');
                    } else if (settings.stretch_type === 'pixinsight_stf') {
                        linearParamsDiv.classList.add('hidden');
                        pixinsightParamsDiv.classList.remove('hidden');
                    }
                    
                    // Set range values
                    rangeInputs.linearLow.input.value = settings.linear_low_percent;
                    rangeInputs.linearLow.value.textContent = settings.linear_low_percent;
                    
                    rangeInputs.linearHigh.input.value = settings.linear_high_percent;
                    rangeInputs.linearHigh.value.textContent = settings.linear_high_percent;
                    
                    rangeInputs.stfShadow.input.value = settings.stf_shadow_clip;
                    rangeInputs.stfShadow.value.textContent = settings.stf_shadow_clip;
                    
                    rangeInputs.stfHighlight.input.value = settings.stf_highlight_clip;
                    rangeInputs.stfHighlight.value.textContent = settings.stf_highlight_clip;
                    
                    rangeInputs.stfMidtones.input.value = settings.stf_midtones_balance;
                    rangeInputs.stfMidtones.value.textContent = settings.stf_midtones_balance;
                    
                    rangeInputs.stfStrength.input.value = settings.stf_strength;
                    rangeInputs.stfStrength.value.textContent = settings.stf_strength;
                    
                    // Set apply to subfolders checkbox
                    applyToSubfolders.checked = settings.apply_to_subfolders == 1;
                    
                    // Show success status with info about which folder's settings we're using
                    if (data.effective_folder !== data.requested_folder) {
                        statusDiv.textContent = `Using settings inherited from ${data.effective_folder}`;
                        statusDiv.className = 'mt-4 text-sm text-yellow-400';
                    } else {
                        statusDiv.textContent = 'Settings loaded successfully';
                        statusDiv.className = 'mt-4 text-sm text-green-400';
                        
                        // Hide status after a few seconds
                        setTimeout(() => {
                            statusDiv.classList.add('hidden');
                        }, 3000);
                    }
                } else {
                    // Show error
                    statusDiv.textContent = data.error || 'Failed to load settings';
                    statusDiv.className = 'mt-4 text-sm text-red-400';
                }
            })
            .catch(error => {
                statusDiv.textContent = 'Error: ' + error.message;
                statusDiv.className = 'mt-4 text-sm text-red-400';
            });
    }
    
    // Save settings
    saveButton.addEventListener('click', () => {
        const currentFolder = document.getElementById('current-folder-path').textContent;
        const stretchType = document.querySelector('input[name="stretch-type"]:checked').value;
        
        const settings = {
            folder_path: currentFolder,
            stretch_type: stretchType,
            linear_low_percent: parseFloat(rangeInputs.linearLow.input.value),
            linear_high_percent: parseFloat(rangeInputs.linearHigh.input.value),
            stf_shadow_clip: parseFloat(rangeInputs.stfShadow.input.value),
            stf_highlight_clip: parseFloat(rangeInputs.stfHighlight.input.value),
            stf_midtones_balance: parseFloat(rangeInputs.stfMidtones.input.value),
            stf_strength: parseFloat(rangeInputs.stfStrength.input.value),
            apply_to_subfolders: applyToSubfolders.checked ? 1 : 0
        };
        
        // Show saving status
        statusDiv.textContent = 'Saving settings...';
        statusDiv.className = 'mt-4 text-sm text-blue-400';
        statusDiv.classList.remove('hidden');
        
        fetch('/api/folder_stretch_settings.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(settings)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                statusDiv.textContent = data.message || 'Settings saved successfully';
                statusDiv.className = 'mt-4 text-sm text-green-400';
                
                // Hide status after a few seconds
                setTimeout(() => {
                    statusDiv.classList.add('hidden');
                }, 3000);
            } else {
                // Show error
                statusDiv.textContent = data.error || 'Failed to save settings';
                statusDiv.className = 'mt-4 text-sm text-red-400';
            }
        })
        .catch(error => {
            statusDiv.textContent = 'Error: ' + error.message;
            statusDiv.className = 'mt-4 text-sm text-red-400';
        });
    });
    
    // Reindex button - show confirmation
    reindexButton.addEventListener('click', () => {
        const currentFolder = document.getElementById('current-folder-path').textContent;
        if (confirm(`Reindex folder ${currentFolder}? This may take some time.`)) {
            // Show processing status
            statusDiv.textContent = 'Reindexing in progress... This may take some time.';
            statusDiv.className = 'mt-4 text-sm text-blue-400';
            statusDiv.classList.remove('hidden');
            
            // Disable button to prevent multiple clicks
            reindexButton.disabled = true;
            
            // Execute reindexing via Docker Python container
            fetch('/api/reindex_folder.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    folder: currentFolder.replace(/^\//, ''), // Remove leading slash
                    force: true
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    statusDiv.textContent = 'Reindexing completed successfully. Refresh the page to see updated thumbnails.';
                    statusDiv.className = 'mt-4 text-sm text-green-400';
                } else {
                    statusDiv.textContent = data.error || 'Reindexing failed';
                    statusDiv.className = 'mt-4 text-sm text-red-400';
                }
                // Re-enable button
                reindexButton.disabled = false;
            })
            .catch(error => {
                statusDiv.textContent = 'Error: ' + error.message;
                statusDiv.className = 'mt-4 text-sm text-red-400';
                reindexButton.disabled = false;
            });
        }
    });
    
    // Load settings on page load
    loadFolderSettings();
});
</script>
