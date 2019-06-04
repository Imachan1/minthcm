<form id="<%= form_name %>" name="<%= form_name %>"><b><%= MOD.LBL_EMPLOYEE_NAME %></b>:<div class="col-xs-12 col-sm-12 edit-view-field  yui-ac" type="relate" field="<%= relate_field_name %>_name">
        <input type="text" name="<%= relate_field_name %>_name" class="vt_formulaSelector sqsEnabled yui-ac-input" tabindex="" id="<%= relate_field_name %>_name" size="" value="" title="" autocomplete="off">
        <input class="vt_formulaSelector" type="hidden" name="<%= relate_field_name %>_id" id="<%= relate_field_name %>_id" value="">
        <span class="id-ff multiple">
            <button type="button" name="btn_<%= relate_field_name %>_name" id="btn_<%= relate_field_name %>_name" tabindex="" title="Wybierz użytkownika" class="button firstChild" value="Wybierz użytkownika" onclick="open_popup( '<%= relate_field_target_module %>', 600, 400, '', true, false,
                    {&quot;call_back_function&quot;:&quot;viewTools.form.function.set_return&quot;,&quot;form_name&quot;:&quot;<%= form_name %>&quot;,&quot;field_to_name_array&quot;:{&quot;id&quot;:&quot;<%= relate_field_name %>_id&quot;,&quot;name&quot;:&quot;<%= relate_field_name %>_name&quot;}}, 'single', true );">
                <img src="themes/SuiteP/images/id-ff-select.png">
            </button>
            <button type="button" name="btn_clr_<%= relate_field_name %>_name" id="btn_clr_<%= relate_field_name %>_name" tabindex="" title="Wyczyść użytkownika" class="button lastChild" onclick="SUGAR.clearRelateField( this.form, '<%= relate_field_name %>_name', '<%= relate_field_name %>_id' );
                   $( '#<%= relate_field_name %>_name,#<%= relate_field_name %>_id' ).blur();" value="Wyczyść użytkownika">
                <img src="themes/SuiteP/images/id-ff-clear.png">
            </button>
        </span>
    </div>
    <br /><b><%= MOD.LBL_START_DATE %>:</b>
    <div class="col-xs-12 col-sm-12 edit-view-field" type="datetimecombo" field="date_start" style="margin-top: 7px">
        <table border="0" cellpadding="0" cellspacing="0" class="dateTime">
            <tbody>
                <tr valign="middle">
                    <td nowrap="" class="dateTimeComboColumn">
                        <input autocomplete="off" type="text" id="date_start_date" class="datetimecombo_date" value="" size="11" maxlength="10" title="" tabindex="0" onblur="combo_date_start.update();" onchange="combo_date_start.update();">
                        <button type="button" id="date_start_trigger" class="btn btn-danger" onclick="return false;"><span class="suitepicon suitepicon-module-calendar" alt="Wprowadź datę"></span></button>
                    </td>
                    <td nowrap="" class="dateTimeComboColumn">
                        <div id="date_start_time_section" class="datetimecombo_time_section">
                            <span>
                                <select class="datetimecombo_time" size="1" id="date_start_hours" tabindex="0">
                                    <option></option>
                                    <option value="00">00</option>
                                    <option value="01">01</option>
                                    <option value="02">02</option>
                                    <option value="03">03</option>
                                    <option value="04">04</option>
                                    <option value="05">05</option>
                                    <option value="06">06</option>
                                    <option value="07">07</option>
                                    <option value="08">08</option>
                                    <option value="09">09</option>
                                    <option value="10">10</option>
                                    <option value="11">11</option>
                                    <option value="12">12</option>
                                    <option value="13">13</option>
                                    <option value="14">14</option>
                                    <option value="15">15</option>
                                    <option value="16">16</option>
                                    <option value="17">17</option>
                                    <option value="18">18</option>
                                    <option value="19">19</option>
                                    <option value="20">20</option>
                                    <option value="21">21</option>
                                    <option value="22">22</option>
                                    <option value="23">23</option>
                                </select>
                                <select class="datetimecombo_time" size="1" id="date_start_minutes" tabindex="0" onchange="combo_date_start.update();">
                                    <option></option>
                                    <option value="00">00</option>
                                    <option value="15">15</option>
                                    <option value="30">30</option>
                                    <option value="45">45</option>
                                </select>
                            </span>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
        <input type="hidden" class="DateTimeCombo" id="date_start" name="date_start" value="">
    </div>
</form>