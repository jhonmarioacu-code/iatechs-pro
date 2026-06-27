export function ThemeBootScript() {
  const script = `
    (function () {
      try {
        var savedTheme = localStorage.getItem("iatechs-theme");
        var prefersDark = window.matchMedia("(prefers-color-scheme: dark)").matches;
        var isDark = savedTheme ? savedTheme === "dark" : prefersDark;
        if (isDark) {
          document.documentElement.classList.add("dark");
        } else {
          document.documentElement.classList.remove("dark");
        }
      } catch (error) {
        document.documentElement.classList.remove("dark");
      }
    })();
  `;

  return <script dangerouslySetInnerHTML={{ __html: script }} />;
}
