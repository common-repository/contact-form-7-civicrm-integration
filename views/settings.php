<div id="wpbody">
  <div id="wpbody-content">
    <div class="wrap">
      <h1><?php esc_html_e( 'CiviCRM Settings' , 'contact-form-7-civicrm-integration');?></h1>

      <?php echo cf7_civi_admin::validate(); ?>

      <div><?php esc_html_e( 'Leave these settings blank if CiviCRM is installed on the same WordPress server', 'contact-form-7-civicrm-integration' );?></div>

      <form name="cf7_civi_admin" id="cf7_civi_admin" action="<?php echo esc_url( cf7_civi_admin::get_page_url() ); ?>" method="POST">
        <table class="form-table" role="presentation">
          <tbody>
            <tr>
              <th width="20%" align="left" scope="row"><?php esc_html_e('CiviCRM Server', 'contact-form-7-civicrm-integration');?></th>
              <td width="5%"/>
              <td align="left">
                <span><input id="host" name="host" type="text" size="15" value="<?php echo esc_attr( cf7_civi_settings::getHost() ); ?>" class="regular-text code"></span>
              </td>
            </tr>

            <tr>
              <th width="20%" align="left" scope="row"><?php esc_html_e('CiviCRM Path', 'contact-form-7-civicrm-integration');?></th>
              <td width="5%"/>
              <td align="left">
                <span><input id="path" name="path" type="text" size="15" value="<?php echo esc_attr( cf7_civi_settings::getPath() ); ?>" class="regular-text code"></span>
              </td>
            </tr>

            <tr>
              <th width="20%" align="left" scope="row"><?php esc_html_e('CiviCRM Site Key', 'contact-form-7-civicrm-integration');?></th>
              <td width="5%"/>
              <td align="left">
                <span><input id="site_key" name="site_key" type="text" size="15" value="<?php echo esc_attr( cf7_civi_settings::getSiteKey() ); ?>" class="regular-text code"></span>
              </td>
            </tr>

            <tr>
              <th width="20%" align="left" scope="row"><?php esc_html_e('CiviCRM API Key', 'contact-form-7-civicrm-integration');?></th>
              <td width="5%"/>
              <td align="left">
                <span><input id="api_key" name="api_key" type="text" size="15" value="<?php echo esc_attr( cf7_civi_settings::getApiKey() ); ?>" class="regular-text code"></span>
              </td>
            </tr>

          </tbody>
        </table>

        <?php wp_nonce_field(cf7_civi_admin::NONCE) ?>
        <p class="submit">
          <input type="hidden" name="action" value="enter-key">
          <input type="submit" name="submit" id="submit" class="button button-primary" value="<?php esc_attr_e('Save Changes and Validate', 'contact-form-7-civicrm-integration');?>">
        </p>

      </form>
    </div>
  </div>
</div>
