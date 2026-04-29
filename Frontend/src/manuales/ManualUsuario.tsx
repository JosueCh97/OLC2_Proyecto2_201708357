/** Manual de Usuario — Compilador Golampi */

function ImagePlaceholder({ label }: { label: string }) {
  return (
    <figure className="img-placeholder">
      <div className="img-placeholder-box" aria-hidden="true">
        <span>🖼</span>
        <span>{label}</span>
      </div>
      <figcaption>{label}</figcaption>
    </figure>
  )
}

function ManualUsuario() {
  return (
    <article className="rounded-2xl border border-[var(--line)] bg-[var(--panel)] p-5 md:p-7">
      <header className="mb-5 border-b border-[var(--line)] pb-4">
        <p className="m-0 text-xs uppercase tracking-[0.18em] text-[var(--muted)]">
          OLC2 P2 — USAC · 2026
        </p>
        <h2 className="m-0 mt-1 text-2xl font-semibold text-[var(--ink)]">
          Manual de Usuario: Compilador Golampi → ARM64
        </h2>
        <p className="mt-2 text-sm text-[var(--muted)]">
          Guía práctica para redactar, compilar y revisar programas Golampi
          desde la interfaz web.
        </p>
      </header>

      {/* Índice */}
      <section className="mb-6">
        <h3 className="m-0 text-sm font-semibold uppercase tracking-[0.12em] text-[var(--muted)]">
          Índice
        </h3>
        <ol className="mt-3 list-decimal space-y-1 pl-5 text-sm text-[var(--ink)]">
          <li>Requisitos y Acceso</li>
          <li>Interfaz Gráfica y Navegación</li>
          <li>Flujo de Trabajo: Compilar un Programa</li>
          <li>Atajos de Teclado</li>
          <li>Sintaxis y Estructuras del Lenguaje</li>
          <li>Código ARM64 Generado</li>
          <li>Reportes y Descargas</li>
          <li>Errores Comunes y Soluciones</li>
        </ol>
      </section>

      <section className="space-y-4">

        {/* 1 */}
        <details open className="rounded-xl border border-[var(--line)] bg-[var(--card)] p-4">
          <summary className="cursor-pointer text-base font-semibold text-[var(--ink)]">
            1. Requisitos y Acceso
          </summary>
          <div className="mt-3 space-y-2">
            <p className="text-sm leading-relaxed text-[var(--ink)]">
              La interfaz se levanta con el servidor Vite. Asegúrate de tener el backend PHP
              activo en <code>http://127.0.0.1:8000</code> antes de compilar.
            </p>
            <pre className="overflow-auto rounded-lg border border-[var(--line)] bg-[#111922] p-3 text-xs text-[#d7f2ff]">
{`# Desde la raíz del proyecto:
./Run.sh

# El script levanta automáticamente:
#   Backend  → http://127.0.0.1:8000  (PHP + router.php)
#   Frontend → http://127.0.0.1:5173  (Vite dev server)`}
            </pre>
            <ImagePlaceholder label="Captura: terminal con Run.sh iniciado correctamente" />
          </div>
        </details>

        {/* 2 */}
        <details open className="rounded-xl border border-[var(--line)] bg-[var(--card)] p-4">
          <summary className="cursor-pointer text-base font-semibold text-[var(--ink)]">
            2. Interfaz Gráfica y Navegación
          </summary>
          <div className="mt-3 space-y-3">
            <p className="text-sm leading-relaxed text-[var(--ink)]">
              La interfaz tiene tres pestañas principales: <strong>Compilador</strong>,{' '}
              <strong>Reportes</strong> y <strong>Manual</strong>.
            </p>
            <ImagePlaceholder label="Captura: vista general de la interfaz con las tres pestañas" />
            <ul className="list-disc space-y-2 pl-5 text-sm leading-relaxed text-[var(--ink)]">
              <li>
                <strong>Compilador</strong> — editor de código a la izquierda y salida
                ARM64 + consola del intérprete a la derecha.
              </li>
              <li>
                <strong>Reportes</strong> — tabla de tokens, errores léxicos, sintácticos,
                semánticos y tabla de símbolos. Permite descargar los resultados.
              </li>
              <li>
                <strong>Manual</strong> — esta documentación.
              </li>
            </ul>
            <details open className="rounded-lg border border-[var(--line)] bg-[var(--panel)] p-3">
              <summary className="cursor-pointer text-sm font-semibold text-[var(--ink)]">
                Botones de la barra de herramientas
              </summary>
              <ul className="mt-2 list-disc space-y-1 pl-5 text-sm text-[var(--ink)]">
                <li><strong>Cargar</strong> — abre el selector de archivos (.go / .gol / .txt).</li>
                <li><strong>Guardar</strong> — descarga el contenido del editor como archivo .gol.</li>
                <li><strong>Compilar</strong> — envía el código al backend, genera ARM64 y muestra resultados.</li>
                <li><strong>Limpiar</strong> — borra editor, consola y código ARM64.</li>
                <li><strong>↓ .s</strong> — descarga el ensamblador generado (solo aparece cuando hay código ARM64).</li>
                <li><strong>Copiar</strong> — copia el contenido del editor o del panel ARM64 al portapapeles.</li>
              </ul>
            </details>
            <ImagePlaceholder label="Captura: barra de herramientas con los botones etiquetados" />
          </div>
        </details>

        {/* 3 */}
        <details open className="rounded-xl border border-[var(--line)] bg-[var(--card)] p-4">
          <summary className="cursor-pointer text-base font-semibold text-[var(--ink)]">
            3. Flujo de Trabajo: Compilar un Programa
          </summary>
          <div className="mt-3 space-y-3">
            <ol className="list-decimal space-y-2 pl-5 text-sm leading-relaxed text-[var(--ink)]">
              <li>
                Escribe el código directamente en el editor o usa <strong>Cargar</strong> para
                abrir un archivo existente.
              </li>
              <li>
                Presiona <strong>Compilar</strong> o el atajo <kbd className="kbd-inline">Ctrl + Enter</kbd>.
              </li>
              <li>
                El panel derecho muestra el ensamblador ARM64 generado (panel azul) y la
                salida del intérprete (panel verde oscuro).
              </li>
              <li>
                Una insignia de estado aparece junto al botón: <em>✓ Sin errores</em> o
                <em> ✗ N errores</em>.
              </li>
              <li>
                Si hay errores, ve a la pestaña <strong>Reportes</strong> para ver el detalle
                con línea y columna.
              </li>
              <li>
                Usa <strong>↓ .s</strong> para descargar el ensamblador y ejecutarlo con
                qemu-aarch64.
              </li>
            </ol>
            <ImagePlaceholder label="Captura: compilación exitosa con código ARM64 visible y badge verde" />
          </div>
        </details>

        {/* 4 */}
        <details open className="rounded-xl border border-[var(--line)] bg-[var(--card)] p-4">
          <summary className="cursor-pointer text-base font-semibold text-[var(--ink)]">
            4. Atajos de Teclado
          </summary>
          <div className="mt-3 overflow-auto rounded-xl border border-[var(--line)]">
            <table className="report-table min-w-full border-collapse">
              <thead>
                <tr>
                  <th>Atajo</th>
                  <th>Acción</th>
                </tr>
              </thead>
              <tbody>
                {[
                  ['Ctrl + Enter', 'Compilar el código actual'],
                  ['Ctrl + A', 'Seleccionar todo el texto del editor (nativo del SO)'],
                  ['Ctrl + Z', 'Deshacer en el editor (nativo del SO)'],
                  ['Tab', 'Navegar entre botones y controles'],
                ].map(([shortcut, action]) => (
                  <tr key={shortcut}>
                    <td><kbd className="kbd-inline">{shortcut}</kbd></td>
                    <td>{action}</td>
                  </tr>
                ))}
              </tbody>
            </table>
          </div>
        </details>

        {/* 5 */}
        <details open className="rounded-xl border border-[var(--line)] bg-[var(--card)] p-4">
          <summary className="cursor-pointer text-base font-semibold text-[var(--ink)]">
            5. Sintaxis y Estructuras del Lenguaje
          </summary>
          <div className="mt-3 space-y-3">
            <p className="text-sm leading-relaxed text-[var(--ink)]">
              Todo programa requiere una función <code>main</code> como punto de entrada.
            </p>

            <details open className="rounded-lg border border-[var(--line)] bg-[var(--panel)] p-3">
              <summary className="cursor-pointer text-sm font-semibold text-[var(--ink)]">
                Tipos de Datos y nil
              </summary>
              <ul className="mt-2 list-disc space-y-1 pl-5 text-sm text-[var(--ink)]">
                <li><code>int32</code> — Entero con signo. Valor por defecto: 0.</li>
                <li><code>float32</code> — Decimal IEEE-754. Valor por defecto: 0.</li>
                <li><code>bool</code> — <code>true</code> o <code>false</code>. Valor por defecto: false.</li>
                <li><code>rune</code> — Carácter en comillas simples (<code>'a'</code>). Valor por defecto: 0.</li>
                <li><code>string</code> — Texto en comillas dobles. Valor por defecto: cadena vacía.</li>
                <li><code>nil</code> — Ausencia de valor. No operable aritméticamente.</li>
              </ul>
            </details>

            <details open className="rounded-lg border border-[var(--line)] bg-[var(--panel)] p-3">
              <summary className="cursor-pointer text-sm font-semibold text-[var(--ink)]">
                Variables y Constantes
              </summary>
              <pre className="mt-2 overflow-auto rounded-lg border border-[var(--line)] bg-[#111922] p-3 text-xs text-[#d7f2ff]">
{`var x int32 = 10          // declaración larga
var y float32             // sin inicializar (0)
var w, z int32 = 1, 2    // declaración múltiple

a, b := 34, 68            // declaración corta (infiere tipo)

const pi float32 = 3.14   // constante (inmutable)`}
              </pre>
            </details>

            <details open className="rounded-lg border border-[var(--line)] bg-[var(--panel)] p-3">
              <summary className="cursor-pointer text-sm font-semibold text-[var(--ink)]">
                Operadores
              </summary>
              <ul className="mt-2 list-disc space-y-1 pl-5 text-sm text-[var(--ink)]">
                <li><strong>Aritméticos:</strong> +, -, *, /, %, ^ (potencia)</li>
                <li><strong>Asignación:</strong> =, +=, -=, *=, /=, :=</li>
                <li><strong>Relacionales:</strong> ==, !=, &gt;, &gt;=, &lt;, &lt;=</li>
                <li><strong>Lógicos:</strong> &amp;&amp;, ||, ! (con cortocircuito)</li>
                <li><strong>Incremento/Decremento:</strong> x++, x--</li>
                <li><strong>Punteros:</strong> &amp;x (referencia), *p (desreferencia)</li>
              </ul>
            </details>

            <details open className="rounded-lg border border-[var(--line)] bg-[var(--panel)] p-3">
              <summary className="cursor-pointer text-sm font-semibold text-[var(--ink)]">
                Control de Flujo
              </summary>
              <pre className="mt-2 overflow-auto rounded-lg border border-[var(--line)] bg-[#111922] p-3 text-xs text-[#d7f2ff]">
{`// if / else if / else
if x > 0 {
  fmt.Println("Positivo")
} else if x == 0 {
  fmt.Println("Cero")
} else {
  fmt.Println("Negativo")
}

// switch
switch dia {
  case 1:
    fmt.Println("Lunes")
  case 2, 3:
    fmt.Println("Martes o Miércoles")
  default:
    fmt.Println("Otro día")
}

// for clásico
for i := 0; i < 5; i++ {
  fmt.Println(i)
}

// for condicional (while)
for x > 0 {
  x--
}

// for infinito con break
for {
  if condicion { break }
}`}
              </pre>
            </details>

            <details open className="rounded-lg border border-[var(--line)] bg-[var(--panel)] p-3">
              <summary className="cursor-pointer text-sm font-semibold text-[var(--ink)]">
                Arreglos
              </summary>
              <pre className="mt-2 overflow-auto rounded-lg border border-[var(--line)] bg-[#111922] p-3 text-xs text-[#d7f2ff]">
{`// 1D
var nums [5]int32
nums[0] = 10
a := [3]int32{1, 2, 3}

// 2D (matriz)
var mat [2][3]int32 = [2][3]int32{
  {1, 2, 3},
  {4, 5, 6},
}

// 3D (cubo)
cubo := [2][2][2]int32{
  {{1, 2}, {3, 4}},
  {{5, 6}, {7, 8}},
}`}
              </pre>
            </details>

            <details open className="rounded-lg border border-[var(--line)] bg-[var(--panel)] p-3">
              <summary className="cursor-pointer text-sm font-semibold text-[var(--ink)]">
                Funciones y Retorno Múltiple
              </summary>
              <pre className="mt-2 overflow-auto rounded-lg border border-[var(--line)] bg-[#111922] p-3 text-xs text-[#d7f2ff]">
{`// Función con retorno múltiple
func division(a int32, b int32) (int32, int32) {
  return a / b, a % b
}

// Llamada con retorno múltiple
cociente, residuo := division(17, 5)

// Función con puntero a arreglo
func duplicar(arr *[3]int32) {
  for i := 0; i < 3; i++ {
    (*arr)[i] = (*arr)[i] * 2
  }
}

func main() {
  var nums [3]int32 = [3]int32{1, 2, 3}
  duplicar(&nums)
  fmt.Println(nums[0])  // 2
}`}
              </pre>
            </details>

            <details open className="rounded-lg border border-[var(--line)] bg-[var(--panel)] p-3">
              <summary className="cursor-pointer text-sm font-semibold text-[var(--ink)]">
                Funciones Embebidas
              </summary>
              <ul className="mt-2 list-disc space-y-1 pl-5 text-sm text-[var(--ink)]">
                <li><code>fmt.Println(a, b, …)</code> — imprime argumentos separados por espacio + salto de línea.</li>
                <li><code>fmt.Print(a, b, …)</code> — igual pero sin salto de línea final.</li>
                <li><code>len(s)</code> — longitud de string o arreglo (en tiempo de compilación para arreglos).</li>
                <li><code>now()</code> — retorna fecha y hora actual como <code>"YYYY-MM-DD HH:MM:SS"</code>.</li>
                <li><code>substr(s, inicio, longitud)</code> — extrae un fragmento de texto.</li>
                <li><code>typeOf(expr)</code> — retorna el tipo como texto: <code>"int32"</code>, <code>"float32"</code>, etc.</li>
                <li><code>int32(x)</code>, <code>float32(x)</code>, <code>rune(x)</code> — conversión explícita de tipos.</li>
              </ul>
            </details>
          </div>
        </details>

        {/* 6 */}
        <details open className="rounded-xl border border-[var(--line)] bg-[var(--card)] p-4">
          <summary className="cursor-pointer text-base font-semibold text-[var(--ink)]">
            6. Código ARM64 Generado
          </summary>
          <div className="mt-3 space-y-3">
            <p className="text-sm leading-relaxed text-[var(--ink)]">
              El panel <strong>Código ARM64</strong> muestra el ensamblador AArch64 listo
              para ser ensamblado con <code>aarch64-linux-gnu-as</code> y ejecutado con
              <code> qemu-aarch64</code>.
            </p>
            <pre className="overflow-auto rounded-lg border border-[var(--line)] bg-[#111922] p-3 text-xs text-[#d7f2ff]">
{`# Descargar el .s desde la interfaz, luego:
aarch64-linux-gnu-as programa.s -o programa.o
aarch64-linux-gnu-ld programa.o -o programa
qemu-aarch64 ./programa`}
            </pre>
            <p className="text-sm leading-relaxed text-[var(--ink)]">
              El botón <strong>Copiar</strong> copia todo el ensamblador al portapapeles.
              El botón <strong>↓ .s</strong> lo descarga como archivo.
            </p>
            <ImagePlaceholder label="Captura: panel ARM64 con código generado y botón de copia" />
          </div>
        </details>

        {/* 7 */}
        <details open className="rounded-xl border border-[var(--line)] bg-[var(--card)] p-4">
          <summary className="cursor-pointer text-base font-semibold text-[var(--ink)]">
            7. Reportes y Descargas
          </summary>
          <div className="mt-3 space-y-2">
            <ul className="list-disc space-y-2 pl-5 text-sm leading-relaxed text-[var(--ink)]">
              <li>
                <strong>Resumen General</strong> — contadores de tokens, errores y símbolos
                de la última compilación.
              </li>
              <li>
                <strong>Tokens</strong> — tipo, lexema, línea y columna de cada token
                reconocido por el analizador léxico.
              </li>
              <li>
                <strong>Errores Léxicos / Sintácticos / Semánticos</strong> — descripción
                detallada con número de línea y columna.
              </li>
              <li>
                <strong>Tabla de Símbolos</strong> — variables y funciones registradas,
                con tipo, entorno y posición en el fuente.
              </li>
            </ul>
            <ImagePlaceholder label="Captura: pestaña Reportes con tabla de tokens activa" />
            <ImagePlaceholder label="Captura: tabla de errores semánticos con línea y columna" />
          </div>
        </details>

        {/* 8 */}
        <details open className="rounded-xl border border-[var(--line)] bg-[var(--card)] p-4">
          <summary className="cursor-pointer text-base font-semibold text-[var(--ink)]">
            8. Errores Comunes y Soluciones
          </summary>
          <div className="mt-3 overflow-auto rounded-xl border border-[var(--line)]">
            <table className="report-table min-w-full border-collapse">
              <thead>
                <tr>
                  <th>Error / Síntoma</th>
                  <th>Causa probable</th>
                  <th>Solución</th>
                </tr>
              </thead>
              <tbody>
                {[
                  [
                    'Error al conectar con API',
                    'Backend PHP no está corriendo',
                    'Ejecuta ./Run.sh o cd BackEnd && php -S 127.0.0.1:8000 router.php',
                  ],
                  [
                    'Sin salida de consola',
                    'El programa no llama a fmt.Println',
                    'Agrega fmt.Println(...) en main()',
                  ],
                  [
                    'Error semántico: variable no declarada',
                    'Usar variable antes de declararla',
                    'Declara con var o :=  antes del uso',
                  ],
                  [
                    'Error sintáctico en llave',
                    'Bloque sin cerrar o mal indentado',
                    'Verifica que cada { tenga su }',
                  ],
                  [
                    'ARM64 ensamblado con error',
                    'Error léxico/sintáctico no detectado',
                    'Revisa la pestaña Reportes antes de ensamblar',
                  ],
                ].map(([err, cause, sol]) => (
                  <tr key={err}>
                    <td className="text-[#f87171]">{err}</td>
                    <td>{cause}</td>
                    <td>{sol}</td>
                  </tr>
                ))}
              </tbody>
            </table>
          </div>
        </details>

      </section>
    </article>
  )
}

export default ManualUsuario