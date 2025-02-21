<div id="${escape(ID)}">
    <div class="grouped-inputs">
        <select class="fa-select" name="${escape(PREFIX)}" id="${escape(PREFIX)}">
            <option value=""></option>
            <option value="fas"# IF C_PREFIX_FAS # selected# ENDIF #>fas</option>
            <option value="far"# IF C_PREFIX_FAR # selected# ENDIF #>far</option>
            <option value="fab"# IF C_PREFIX_FAB # selected# ENDIF #>fab</option>
        </select>
        <input
            type="text"
            name="${escape(ICON)}"
            id="${escape(ICON)}"
            value="{ICON_VALUE}"
            class="grouped-element fa-icon # IF C_READONLY #low-opacity # ENDIF #${escape(CLASS)}"
            placeholder="icon name *"
            # IF C_DISABLED # disabled="disabled"# ENDIF #
            # IF C_READONLY # readonly="readonly"# ENDIF # />
        <span id="${escape(SELECTED)}" class="grouped-element icon-selected"></span>
    </div>
    <ul id="${escape(ICON_LIST)}" class="icon-list hidden"></ul>
</div>
<script>
    class IconSelector_${escape(ID)} {
        constructor(prefix, icon, iconList, selected) {
            this.prefixSelect = document.getElementById(prefix);
            this.inputIcon = document.getElementById(icon);
            this.iconList = document.getElementById(iconList);
            this.selectedIcon = document.getElementById(selected);
            this.prefixChoice = '';
            this.choosenList = [];

            this.inputIcon.placeholder = '{@form.icon.select}';
            this.inputIcon.disabled = true;

            this.initEventListeners();
        }

        initEventListeners() {
            this.prefixSelect.addEventListener('change', this.handlePrefixChange.bind(this));
            this.inputIcon.addEventListener('focus', this.handleIconFocus.bind(this));
            this.inputIcon.addEventListener('input', this.handleIconInput.bind(this));
            this.inputIcon.addEventListener('blur', this.handleIconBlur.bind(this));
            this.inputIcon.addEventListener('click', this.handleIconClick.bind(this));
        }

        handlePrefixChange() {
            this.prefixChoice = this.prefixSelect.value;
            this.inputIcon.value = '';
            this.selectedIcon.innerHTML = '';

            switch (this.prefixChoice) {
                case 'fab':
                    this.inputIcon.placeholder = '{@form.icon.input}';
                    this.inputIcon.disabled = false;
                    this.choosenList = [{FAB}]; // Replace with actual list
                    break;
                case 'fas':
                case 'far':
                    this.inputIcon.placeholder = '{@form.icon.input}';
                    this.inputIcon.disabled = false;
                    this.choosenList = [{FAS}]; // Replace with actual list
                    break;
                default:
                    this.inputIcon.placeholder = '{@form.icon.select}';
                    this.inputIcon.disabled = true;
                    this.choosenList = [];
            }
        }

        handleIconFocus() {
            this.iconList.classList.remove('hidden');
            this.displayIcons(this.choosenList);
        }

        handleIconInput() {
            const query = this.inputIcon.value.toLowerCase();
            const filteredIcons = this.choosenList.filter(icon => this.matchQuery(icon, query));

            if (query === '') {
                this.selectedIcon.innerHTML = '';
            } else {
                this.selectedIcon.innerHTML = '<i class="' + this.prefixChoice + ' ' + this.inputIcon.value + '"></i>';
            }

            this.displayIcons(filteredIcons);
        }

        handleIconBlur() {
            setTimeout(() => {
                this.iconList.classList.add('hidden');
                this.iconList.innerHTML = '';
            }, 200);
        }

        handleIconClick() {
            setTimeout(() => {
                this.iconList.classList.remove('hidden');
                this.displayIcons(this.choosenList);
            }, 200);
        }

        matchQuery(icon, query) {
            const iconName = icon.replace('fa-', '');
            return query.split('').every(char => iconName.includes(char));
        }

        displayIcons(icons) {
            this.iconList.innerHTML = '';

            if (icons.length === 0) {
                this.iconList.innerHTML = '<li>{@form.icon.none}</li>';
                return;
            }

            icons.forEach(icon => {
                const li = document.createElement('li');
                li.innerHTML = '<span aria-label="' + icon + '"><i class="' + this.prefixChoice + ' fa-fw fa-' + icon + '"></i><span>';
                li.addEventListener('click', () => {
                    this.inputIcon.value = 'fa-' + icon + '';
                    this.iconList.innerHTML = '';
                    this.inputIcon.focus();
                    this.selectedIcon.innerHTML = '<i class="' + this.prefixChoice + ' fa-' + icon + '"></i>';
                });
                this.iconList.appendChild(li);
            });
        }
    }

    const iconSelector_${escape(ID)} = new IconSelector_${escape(ID)}('${escape(PREFIX)}', '${escape(ICON)}', '${escape(ICON_LIST)}', '${escape(SELECTED)}');
</script>
