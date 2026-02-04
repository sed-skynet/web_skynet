wp.domReady(() => {
  const { registerPlugin } = wp.plugins;
  const { PluginSidebar, PluginSidebarMoreMenuItem } = wp.editPost;
  const { PanelBody, Button } = wp.components;
  const { createBlock } = wp.blocks;
  const { select, dispatch } = wp.data;
  const { createElement: el, Fragment } = wp.element;

  const insertBubble = (type) => {
    const selected = select('core/block-editor').getSelectedBlockClientId();
    const index = selected
      ? select('core/block-editor').getBlockIndex(selected) + 1
      : select('core/block-editor').getBlocks().length;

    let block;

    if (type === 'intro') {
      block = createBlock('core/group', { className: 'news-bubble news-intro' }, [
        createBlock('core/paragraph', { placeholder: '✍️ Entradilla: escribe aquí' })
      ]);
    }

    if (type === 'context') {
      block = createBlock('core/group', { className: 'news-bubble news-context' }, [
        createBlock('core/paragraph', { placeholder: '📌 Contexto' })
      ]);
    }

    if (type === 'body') {
      block = createBlock('core/group', { className: 'news-bubble news-body' }, [
        createBlock('core/paragraph', { placeholder: '🧠 Desarrollo' })
      ]);
    }

    if (block) {
      dispatch('core/block-editor').insertBlocks(block, index);
    }
  };

  const Sidebar = () =>
    el(
      Fragment,
      {},
      el(
        PluginSidebarMoreMenuItem,
        { target: 'news-bubbles-sidebar', icon: 'welcome-add-page' },
        'Bocadillos editoriales'
      ),
      el(
        PluginSidebar,
        { name: 'news-bubbles-sidebar', title: 'Bocadillos editoriales' },
        el(
          PanelBody,
          { title: 'Añadir sección', initialOpen: true },
          el(Button, { isPrimary: true, onClick: () => insertBubble('intro') }, '➕ Entradilla'),
          el(Button, { isSecondary: true, onClick: () => insertBubble('context') }, '➕ Contexto'),
          el(Button, { isSecondary: true, onClick: () => insertBubble('body') }, '➕ Desarrollo')
        )
      )
    );

  registerPlugin('news-bubbles-plugin', { render: Sidebar });
});
