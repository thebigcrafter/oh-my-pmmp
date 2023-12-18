import { defineConfig } from "vitepress";

// https://vitepress.dev/reference/site-config
export default defineConfig({
  base: "/",
  title: "Oh My PMMP",
  description: "A powerful plugin manager for PocketMine-MP",
  themeConfig: {
    logo: "../icon.png",
    nav: [
      { text: "Home", link: "/" },
    ],

    sidebar: [
      {
        text: "Getting Started",
        items: [
          { text: "Installation", link: "/installation" },
        ],
      },
      {
        text: "Customization",
        items: [
          { text: "Configuration", link: "/configuration" },
        ],
      },
      {
        text: "Guide",
        items: [
          { text: "Commands", link: "/commands" },
        ],
      },
    ],

    socialLinks: [
      {
        icon: "discord",
        link: "https://discord.gg/JgCuSFA8tJ",
      },
      { icon: "github", link: "https://github.com/thebigcrafter/oh-my-pmmp" },
    ],
    footer: {
      message: "Licensed under the GPL-3.0 License.",
      copyright: "Copyright © 2023 thebigcrafter",
    },
    
    editLink: {
      pattern: 'https://github.com/thebigcrafter/oh-my-pmmp/edit/main/docs/:path',
      text: 'Suggest changes to this page'
    }
  },
});

