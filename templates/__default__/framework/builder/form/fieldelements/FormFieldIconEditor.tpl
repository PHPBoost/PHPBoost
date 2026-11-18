<div id="${escape(ID)}">
    <div class="grouped-inputs">
        <select class="icon-select" name="${escape(ID)}_prefix" id="${escape(ID)}_prefix">
            <option value=""></option>
            <option value="fas"# IF C_PREFIX_FAS # selected# ENDIF #>{@form.icon.solid}</option>
            <option value="far"# IF C_PREFIX_FAR # selected# ENDIF #>{@form.icon.regular}</option>
            <option value="fab"# IF C_PREFIX_FAB # selected# ENDIF #>{@form.icon.brand}</option>
            <option value="fa iboost"# IF C_PREFIX_FAB # selected# ENDIF #>{@form.icon.iboost}</option>
        </select>
        <input
            type="text"
            name="${escape(ID)}_icon"
            id="${escape(ID)}_icon"
            value="{ICON_VALUE}"
            class="grouped-element fa-icon # IF C_READONLY #low-opacity # ENDIF #${escape(CLASS)}" />
        <span id="${escape(ID)}_selected" class="grouped-element icon-selected"></span>
    </div>
    <ul id="${escape(ID)}_icon_list" class="icon-list hidden"></ul>
</div>
<script>
    class IconSelector_${escape(ID)} {
        constructor(prefix, icon, iconList, selected) {
            this.prefixSelect = document.getElementById(prefix);
            this.iconInput = document.getElementById(icon);
            this.iconList = document.getElementById(iconList);
            this.selectedIcon = document.getElementById(selected);
            this.prefixChoice = '';
            this.choosenList = [];

            this.iconInput.placeholder = '{@form.icon.select}';
            this.iconInput.disabled = true;

            this.initEventListeners();
            window.onload = this.checkValuesOnLoad.bind(this);
        }

        initEventListeners() {
            this.prefixSelect.addEventListener('change', this.handlePrefixChange.bind(this));
            this.iconInput.addEventListener('focus', this.handleIconFocus.bind(this));
            this.iconInput.addEventListener('input', this.handleIconInput.bind(this));
            this.iconInput.addEventListener('blur', this.handleIconBlur.bind(this));
            this.iconInput.addEventListener('click', this.handleIconClick.bind(this));
        }

        checkValuesOnLoad() {
            if (this.prefixSelect.value) {
                this.prefixChoice = this.prefixSelect.value;
                this.iconInput.disabled = false;
                this.handlePrefixChoice();

                if (this.iconInput.value) {
                    this.selectedIcon.innerHTML = '<i class="' + this.prefixChoice + ' fa-' + this.iconInput.value.replace('fa-', '') + '"></i>';
                }
            }
        }

        handlePrefixChange() {
            this.prefixChoice = this.prefixSelect.value;
            this.iconInput.value = '';
            this.selectedIcon.innerHTML = '';
            this.handlePrefixChoice();
        }

        handlePrefixChoice() {
            switch (this.prefixChoice) {
                case 'fab':
                    this.iconInput.placeholder = '{@form.icon.input}';
                    this.iconInput.disabled = false;
                    this.choosenList = [{FAB}];
                    break;
                case 'fas':
                    this.iconInput.placeholder = '{@form.icon.input}';
                    this.iconInput.disabled = false;
                    this.choosenList = [{FAS}];
                    break;
                case 'far':
                    this.iconInput.placeholder = '{@form.icon.input}';
                    this.iconInput.disabled = false;
                    this.choosenList = [{FAR}];
                    break;
                case 'fa iboost':
                    this.iconInput.placeholder = '{@form.icon.input}';
                    this.iconInput.disabled = false;
                    this.choosenList = [{IBOOST}];
                    break;
                default:
                    this.iconInput.placeholder = '{@form.icon.select}';
                    this.iconInput.disabled = true;
                    this.choosenList = [];
            }
        }

        handleIconFocus() {
            this.iconList.classList.remove('hidden');
            this.displayIcons(this.choosenList);
        }

        handleIconInput() {
            const query = this.iconInput.value.toLowerCase();
            const filteredIcons = this.choosenList.filter(icon => this.matchQuery(icon, query));

            if (query === '') {
                this.selectedIcon.innerHTML = '';
            } else {
                this.selectedIcon.innerHTML = '<i class="' + this.prefixChoice + ' ' + this.iconInput.value + '"></i>';
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
                li.innerHTML = '<span aria-label="' + icon + '"><i class="' + this.prefixChoice + ' fa-' + icon + '"></i><span>';
                li.addEventListener('click', () => {
                    this.iconInput.value = 'fa-' + icon + '';
                    this.iconList.innerHTML = '';
                    this.iconInput.focus();
                    this.selectedIcon.innerHTML = '<i class="' + this.prefixChoice + ' fa-' + icon + '"></i>';
                });
                this.iconList.appendChild(li);
            });
        }
    }

    const iconSelector_${escape(ID)} = new IconSelector_${escape(ID)}('${escape(ID)}_prefix', '${escape(ID)}_icon', '${escape(ID)}_icon_list', '${escape(ID)}_selected');
</script>
