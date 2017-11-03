<?php
/**
<p>
In theme settings.yml. If optional parameter password is set user can visit site if knowing the password.
</p>
#code-yml#
plugin:
  wf:
    shutdown:
      data:
        shutdown: true
        message: 'This site is closed with method shutdown in plugin wf/shutdown!'
        password: my_secret_password
#code#
*/
class PluginWfShutdown{
  /**
  <p>
  In theme settings.yml to run event load_theme_config_settings_after.
  <p>
  #code-yml#
  events:
    load_theme_config_settings_after:
      -
        plugin: wf/shutdown
        method: shutdown
  #code#
  */
  public static function event_shutdown($data){
    if(wfArray::get($GLOBALS, 'sys/settings/plugin/wf/shutdown/data/shutdown') && !wfArray::get($_SESSION, 'plugin/wf/shutdown/authenticated')){
      wfArray::set($GLOBALS, 'sys/layout_path', '/plugin/demo/shutdown/layout');
      $filename = ( __DIR__).'/page/shutdown.yml';
      $page = wfFilesystem::loadYml($filename);
      if(wfArray::get($GLOBALS, 'sys/settings/plugin/wf/shutdown/data/message')){
        $page = wfArray::set($page, 'content/message/innerHTML', wfArray::get($GLOBALS, 'sys/settings/plugin/wf/shutdown/data/message'));
      }
      if(wfArray::get($GLOBALS, 'sys/settings/plugin/wf/shutdown/data/password')){
        $page = wfArray::set($page, 'content/form/settings/disabled', false);
        $page = wfArray::set($page, 'content/form/innerHTML/password/attribute/value', wfRequest::get('password'));
        if(wfArray::get($GLOBALS, 'sys/settings/plugin/wf/shutdown/data/password')==wfRequest::get('password')){
          $_SESSION = wfArray::set($_SESSION, 'plugin/wf/shutdown/authenticated', true);
        }
      }
      wfDocument::renderElement($page['content']);
      exit;
    }
  }
}

