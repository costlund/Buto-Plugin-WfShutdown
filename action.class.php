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
  In theme settings.yml to run event document_render_before.
  <p>
  #code-yml#
  events:
    document_render_before:
      -
        plugin: wf/shutdown
        method: shutdown
  #code#
  */
  public static function event_shutdown($data){
    /**
     * Check if shutdown=true and not authenticated.
     */
    if(wfArray::get($GLOBALS, 'sys/settings/plugin/wf/shutdown/data/shutdown') && !wfArray::get($_SESSION, 'plugin/wf/shutdown/authenticated')){
      /**
       * Set layout.
       */
      wfGlobals::setSys('layout_path', '/plugin/demo/shutdown/layout');
      $filename = ( __DIR__).'/page/shutdown.yml';
      $page = wfFilesystem::loadYml($filename);
      /**
       * Check if message is set.
       */
      if(wfArray::get($GLOBALS, 'sys/settings/plugin/wf/shutdown/data/message')){
        $page = wfArray::set($page, 'content/message/innerHTML', wfArray::get($GLOBALS, 'sys/settings/plugin/wf/shutdown/data/message'));
      }
      /**
       * Check if password i set.
       */
      if(wfArray::get($GLOBALS, 'sys/settings/plugin/wf/shutdown/data/password')){
        /**
         * Enabled the form.
         */
        $page = wfArray::set($page, 'content/form/settings/disabled', false);
        $page = wfArray::set($page, 'content/form/innerHTML/password/attribute/value', wfRequest::get('password'));
        /**
         * Check if user has post password and if it's valid.
         */
        if(wfArray::get($GLOBALS, 'sys/settings/plugin/wf/shutdown/data/password')==wfRequest::get('password')){
          $_SESSION = wfArray::set($_SESSION, 'plugin/wf/shutdown/authenticated', true);
          exit("<script>location.href='/';</script>");
        }
      }
      wfDocument::renderElement($page['content']);
      exit;
    }
  }
}
