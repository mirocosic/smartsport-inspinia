// Add style for "null"-value dynamically
var sStyle = '.x-checkbox-null input {\n';
sStyle += '-ms-filter:"progid:DXImageTransform.Microsoft.Alpha(Opacity=50)";\n';
sStyle += 'filter:progid:DXImageTransform.Microsoft.Alpha(Opacity=50);\n';
sStyle += 'opacity:.5;}';
Ext.util.CSS.createStyleSheet(sStyle, 'Ext.ux.form.TriCheckbox');

// Define class Ext.ux.form.TriCheckbox
Ext.define('Ext.ux.form.TriCheckbox', {
    extend: 'Ext.form.field.Checkbox',
    alias: ['widget.xtricheckbox', "widget.tri-checkbox"],
    triState: true, // triState can dynamically be disabled using enableTriState

    values: ['null', '0', '1'], // The values which are toggled through
    checkedClasses: ['x-checkbox-null', '', Ext.baseCSSPrefix + 'form-cb-checked'], // The classes used for the different states

    currentCheck: 0, // internal use: which state we are in?

    getSubmitValue: function() {
        return this.value;
    },    
    getRawValue: function() {
        return this.value;
    },    
    getValue: function() {
        return this.value;
    },
    initValue: function() {
        var me = this;
        me.originalValue = me.lastValue = me.value;
        me.suspendCheckChange++;
        me.setValue(me.value);
        me.suspendCheckChange--;
    },
    setRawValue: function(v) {
        var me = this;

        if (v === false)
            v = '0';
        if (v === true)
            v = '1';
        if (v == null || v == '' || v === undefined)
        {
            if (!this.triState)
                v = '0';
            else
                v = 'null';
        }

        var oldCheck = me.currentCheck;
        me.currentCheck = me.getCheckIndex(v);
        me.value = me.rawValue = me.values[me.currentCheck];

        // Update classes
        var inputEl = me.inputEl;
        if (inputEl)
        {
            inputEl.dom.setAttribute('aria-checked', me.value == '1' ? true : false);
            me['removeCls'](me.checkedClasses[oldCheck])
            me['addCls'](me.checkedClasses[this.currentCheck]);
        }
    },
    // Returns the index from a value to a member of me.values 
    getCheckIndex: function(value) {
        for (var i = 0; i < this.values.length; i++)
        {
            if (value === this.values[i])
            {
                return i;
            }
        }
        return 0;
    },
    // Handels a click on the checkbox
    onBoxClick: function(e) {
        this.toggle();
    },
    // Switches to the next checkbox-state
    toggle: function() {
        var me = this;
        if (!me.disabled && !me.readOnly)
        {
            var check = me.currentCheck;
            check++;
            if (check >= me.values.length)
                check = (me.triState == false) ? 1 : 0;
            this.setValue(me.values[check]);
        }
    },
    // Enables/Disables tristate-handling at runtime (enableTriState(false) gives a 'normal' checkbox)
    enableTriState: function(bTriState) {
        if (bTriState == undefined)
            bTriState = true;
        this.triState = bTriState;
        if (!this.triState)
        {
            this.setValue(this.value);
        }
    },
    // Toggles tristate-handling ar runtime
    toggleTriState: function() {
        this.enableTriState(!this.triState);
    }
});