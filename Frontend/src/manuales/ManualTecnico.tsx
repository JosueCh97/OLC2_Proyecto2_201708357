/** Manual Técnico — Compilador Golampi → ARM64 */

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

function CodeBlock({ code, lang = 'asm' }: { code: string; lang?: string }) {
  return (
    <pre
      className="mt-2 overflow-auto rounded-lg border border-[var(--line)] bg-[#0d1117] p-3 text-xs text-[#79c0ff]"
      data-lang={lang}
    >
      {code}
    </pre>
  )
}

function Section({ id, title, children }: { id: string; title: string; children: React.ReactNode }) {
  return (
    <details id={id} open className="rounded-xl border border-[var(--line)] bg-[var(--card)] p-4">
      <summary className="cursor-pointer text-base font-semibold text-[var(--ink)]">{title}</summary>
      <div className="mt-3 space-y-3">{children}</div>
    </details>
  )
}

function SubSection({ title, children }: { title: string; children: React.ReactNode }) {
  return (
    <details open className="rounded-lg border border-[var(--line)] bg-[var(--panel)] p-3">
      <summary className="cursor-pointer text-sm font-semibold text-[var(--ink)]">{title}</summary>
      <div className="mt-2 space-y-2">{children}</div>
    </details>
  )
}

function p(text: string) {
  return <p className="text-sm leading-relaxed text-[var(--ink)]">{text}</p>
}

function ManualTecnico() {
  return (
    <article className="rounded-2xl border border-[var(--line)] bg-[var(--panel)] p-5 md:p-7">
      <header className="mb-5 border-b border-[var(--line)] pb-4">
        <p className="m-0 text-xs uppercase tracking-[0.18em] text-[var(--muted)]">
          OLC2 P2 — USAC · 2026
        </p>
        <h2 className="m-0 mt-1 text-2xl font-semibold text-[var(--ink)]">
          Manual Técnico: Compilador Golampi → ARM64
        </h2>
        <p className="mt-2 text-sm text-[var(--muted)]">
          Documentación interna de la arquitectura, pipeline y decisiones de diseño
          del generador de código ensamblador AArch64.
        </p>
      </header>

      {/* Índice */}
      <section className="mb-6">
        <h3 className="m-0 text-sm font-semibold uppercase tracking-[0.12em] text-[var(--muted)]">
          Índice
        </h3>
        <ol className="mt-3 list-decimal space-y-1 pl-5 text-sm text-[var(--ink)]">
          <li>Arquitectura General del Compilador</li>
          <li>Gramática ANTLR4 (Golampi.g4)</li>
          <li>Visitor y Construcción del AST</li>
          <li>Generador de Código ARM64</li>
          <li>Convenciones de Registros y Stack Frame</li>
          <li>Helpers de Runtime</li>
          <li>Arreglos y Aritmética de Offsets</li>
          <li>Manejo de Cadenas</li>
          <li>Funciones Embebidas</li>
          <li>Pipeline de Prueba</li>
        </ol>
      </section>

      <section className="space-y-4">

        {/* 1. Arquitectura */}
        <Section id="mt-arquitectura" title="1. Arquitectura General del Compilador">
          {p('El compilador Golampi sigue un pipeline clásico de cuatro etapas: análisis léxico/sintáctico con ANTLR4, construcción de AST con un Visitor PHP personalizado, interpretación directa del AST (Proyecto 1), y finalmente generación de código ARM64 (Proyecto 2).')}
          <ImagePlaceholder label="Diagrama: pipeline completo del compilador (Lexer → Parser → AST → ARM64)" />
          <SubSection title="Tecnologías utilizadas">
            <ul className="list-disc space-y-1 pl-5 text-sm text-[var(--ink)]">
              <li><strong>ANTLR4</strong> (runtime PHP) — análisis léxico y sintáctico</li>
              <li><strong>PHP 8.x</strong> — Visitor, intérprete y generador ARM64</li>
              <li><strong>aarch64-linux-gnu-as</strong> 2.42 — ensamblador GNU para AArch64</li>
              <li><strong>aarch64-linux-gnu-ld</strong> — enlazador (sin libc, syscalls directas)</li>
              <li><strong>qemu-aarch64</strong> 8.2.2 — emulador para ejecutar el binario ELF</li>
              <li><strong>React + Vite + Tailwind CSS</strong> — interfaz web</li>
            </ul>
          </SubSection>
        </Section>

        {/* 2. Gramática */}
        <Section id="mt-gramatica" title="2. Gramática ANTLR4 (Golampi.g4)">
          {p('La gramática define el lenguaje Golampi, un subconjunto académico de Go. El punto de entrada del parser es la regla inicio, que contiene una secuencia de declaraciones de funciones o instrucciones globales.')}
          <ImagePlaceholder label="Captura: extracto de Golampi.g4 (reglas principales)" />
          <SubSection title="Reglas clave">
            <ul className="list-disc space-y-1 pl-5 text-sm text-[var(--ink)]">
              <li><code>inicio</code> — raíz del árbol; llama a <code>$parser-&gt;inicio()</code></li>
              <li><code>funcion</code> — definición <code>func nombre(params) (retornos) {'{ bloque }'}</code></li>
              <li><code>instruccion</code> — if, for, switch, declaración, asignación, return, break, continue</li>
              <li><code>expresion</code> — aritmética, relacional, lógica, llamadas, casteos, literales</li>
              <li><code>tipo</code> — int32, float32, bool, string, rune, arreglos N-dim, punteros</li>
            </ul>
          </SubSection>
          <SubSection title="Tokens especiales">
            <ul className="list-disc space-y-1 pl-5 text-sm text-[var(--ink)]">
              <li>Operador de declaración corta <code>:=</code> (infiere tipo)</li>
              <li>Operador potencia <code>^</code> (no nativo en AArch64, se usa helper <code>__pow_int</code>)</li>
              <li>Puntero <code>&amp;var</code> y desreferencia <code>*var</code></li>
            </ul>
          </SubSection>
        </Section>

        {/* 3. Visitor y AST */}
        <Section id="mt-visitor" title="3. Visitor y Construcción del AST">
          {p('CustomVisitor (App\\Interprete\\CustomVisitor) extiende GolampiBaseVisitor y convierte cada nodo del árbol de parse en un objeto PHP del namespace App\\Instructions o App\\Expressions. El resultado es un array plano de instrucciones de nivel superior.')}
          <ImagePlaceholder label="Diagrama: jerarquía de clases AST (Instruction / Expression y subclases)" />
          <SubSection title="Nodos de instrucción">
            <ul className="list-disc space-y-1 pl-5 text-sm text-[var(--ink)]">
              <li>DeclaracionID / DeclaracionCorta — var x tipo = val / x := val</li>
              <li>Asignacion / AsignacionCompuesta / AsignacionArreglo / AsignacionPuntero</li>
              <li>Si / Para / Segun — control de flujo</li>
              <li>Funcion — define y registra la función en el frame</li>
              <li>Retorno / Romper / Continuar / IncDec</li>
              <li>Imprimir / LlamadaInstr</li>
            </ul>
          </SubSection>
          <SubSection title="Nodos de expresión">
            <ul className="list-disc space-y-1 pl-5 text-sm text-[var(--ink)]">
              <li>Primitivo — literales int, float, bool, string, rune</li>
              <li>AccesoID — lee variable del entorno / frame</li>
              <li>Aritmetico / Relacional / Logico</li>
              <li>Llamada — invocación de función o builtin</li>
              <li>ArregloLiteral / AccesoArreglo</li>
              <li>Casteo — int32(x), float32(x), rune(x)</li>
              <li>Referencia (&amp;) / Desreferencia (*)</li>
            </ul>
          </SubSection>
        </Section>

        {/* 4. Generador ARM64 */}
        <Section id="mt-generador" title="4. Generador de Código ARM64">
          {p('ARM64Generator (App\\Compiler\\ARM64Generator) recorre el array de instrucciones AST emitiendo texto ensamblador GNU AS para AArch64. El generador realiza dos pasadas: primero recolecta todas las funciones (hoisting), luego emite main y el resto.')}
          <ImagePlaceholder label="Diagrama: flujo interno de ARM64Generator (pre-scan → main → funciones → helpers)" />
          <SubSection title="Pre-scan de funciones (hoisting)">
            {p('En la primera pasada, generarPreScan() recorre el AST buscando nodos Funcion. Registra nombre, tipos de parámetros y tipos de retorno en el mapa funcReturnTypes. Esto permite que las llamadas dentro de expresiones conozcan el tipo de retorno sin resolver el AST de la función llamada.')}
          </SubSection>
          <SubSection title="Estructura de un frame emitido">
            <CodeBlock lang="asm" code={`main:
    sub     sp, sp, #N       // reservar frame (N bytes, múltiplo de 16)
    stp     x29, x30, [sp, #0]
    mov     x29, sp

    // … instrucciones …

    ldp     x29, x30, [sp, #0]
    add     sp, sp, #N
    ret`} />
          </SubSection>
          <SubSection title="Gestión de tipos de retorno">
            {p('generarExpresion() retorna un string con el tipo Golampi del resultado (ENTERO, DECIMAL, BOOLEANO, CADENA, CARACTER). El tipo informa al contexto si debe usar registros FPU (d0) o generales (x0) para operaciones subsecuentes.')}
          </SubSection>
        </Section>

        {/* 5. Registros y Stack Frame */}
        <Section id="mt-registros" title="5. Convenciones de Registros y Stack Frame">
          <SubSection title="Layout del stack frame">
            <CodeBlock lang="asm" code={`[x29 +  0]  x29 (fp) del llamador
[x29 +  8]  x30 (lr) del llamador
[x29 + 16]  scratch save slot 0   ← getScratchSaveOffset(0)
[x29 + 24]  scratch save slot 1
[x29 + 32]  scratch save slot 2
[x29 + 40]  scratch save slot 3
[x29 + 48]  scratch save slot 4
[x29 + 56]  scratch save slot 5
[x29 + 64]  parámetro 0 / primera variable local
[x29 + 72]  parámetro 1 / segunda variable …`} />
            {p('Los 6 scratch slots entre x29/x30 y las variables locales garantizan que los operandos intermedios de expresiones binarias sobrevivan a cualquier instrucción bl (AAPCS64 clasifica x0-x15 como caller-saved).')}
          </SubSection>
          <SubSection title="Convención de tipos en registros">
            <ul className="list-disc space-y-1 pl-5 text-sm text-[var(--ink)]">
              <li><strong>ENTERO / BOOLEANO / CARACTER</strong> → x0 (valor entero de 64 bits)</li>
              <li><strong>DECIMAL</strong> → bits IEEE-754 double en x0; se mueve a d0 con <code>fmov d0, x0</code> para operar</li>
              <li><strong>CADENA</strong> → x0 = puntero a bytes UTF-8, x1 = longitud en bytes</li>
              <li><strong>Argumentos a funciones</strong> → x0 … x7 (AAPCS64)</li>
              <li><strong>Retorno múltiple</strong> → x0, x1, x2 … (un registro por valor)</li>
            </ul>
          </SubSection>
          <ImagePlaceholder label="Diagrama: layout del stack frame con valores concretos de offsets" />
        </Section>

        {/* 6. Helpers de Runtime */}
        <Section id="mt-helpers" title="6. Helpers de Runtime">
          {p('Los helpers se emiten al final del ensamblador solo si fueron necesitados (flags needsXxx). Todos siguen el ABI estándar: prólogo con stp/mov y epílogo con ldp/ret.')}
          <SubSection title="__print_int_raw">
            {p('Convierte x0 (entero con signo de 64 bits) a dígitos ASCII y los escribe en stdout mediante syscall write (x8=64). Implementado con división repetida por 10, llenando un buffer temporal en stack.')}
            <ImagePlaceholder label="Diagrama de flujo: __print_int_raw (sign → digits → trim → write)" />
          </SubSection>
          <SubSection title="__print_float_raw">
            {p('Entrada: x0 = bits IEEE-754 double. Separa signo, parte entera (frintz + fcvtzs) y parte fraccionaria (fsub). Multiplica la fracción por 1e9 (fcvtas) para obtener 9 dígitos, recorta ceros finales y emite: [signo] → [entero via __print_int_raw] → [.] → [fracción].')}
            <ImagePlaceholder label="Diagrama de flujo: __print_float_raw (sign → int → . → frac)" />
          </SubSection>
          <SubSection title="__print_bool_raw">
            {p('Compara x0 con 0. Si es distinto escribe el literal "true" (4 bytes); si es cero escribe "false" (5 bytes). Ambas cadenas se ubican en la sección .data.')}
          </SubSection>
          <SubSection title="__pow_int">
            {p('Implementa x0 = base^exp mediante multiplicación repetida. El operador ^ del lenguaje se reescribe como llamada a este helper.')}
          </SubSection>
          <SubSection title="__strlen_setx1">
            {p('Calcula strlen(x0) sin mover x0. Recorre bytes hasta encontrar \\0 y devuelve la longitud en x1. Se usa para cadenas cuya longitud no se conoce en tiempo de compilación.')}
          </SubSection>
          <SubSection title="__now">
            {p('Llama a clock_gettime(CLOCK_REALTIME) con syscall 113. Convierte los segundos Unix a la cadena "YYYY-MM-DD HH:MM:SS" mediante divisiones enteras sucesivas. Devuelve x0=puntero, x1=longitud (19).')}
          </SubSection>
        </Section>

        {/* 7. Arreglos */}
        <Section id="mt-arreglos" title="7. Arreglos y Aritmética de Offsets">
          <SubSection title="Allocación en el frame">
            {p('allocArray(name, dims) reserva product(dims) × 8 bytes contiguos en el stack frame. El offset base apunta al elemento [0][0]…[0]. Para un arreglo [3][4]int32 se reservan 12 slots de 8 bytes = 96 bytes.')}
          </SubSection>
          <SubSection title="Row-major order (emitirOffsetRowMajor)">
            {p('Para un arreglo de dimensiones [D0][D1][D2] con índices [k][i][j], el offset plano es: k×D1×D2 + i×D2 + j. Al final se aplica lsl x9, x9, #3 para convertir a bytes (×8).')}
            <CodeBlock lang="asm" code={`// cubo[k][i][j]  con dims=[2][2][2]
// offset = k*4 + i*2 + j  → lsl ×8
ldr x10, [x29, #slot_k]    // k
mov x11, #4
mul x10, x10, x11
ldr x9,  [x29, #slot_i]    // + i*2
add x9,  x9,  x9
add x10, x10, x9
ldr x9,  [x29, #slot_j]    // + j
add x9,  x10, x9
lsl x9,  x9,  #3            // ×8 → bytes`} />
          </SubSection>
          <SubSection title="Arreglos puntero (*[N]tipo)">
            {p('Cuando un arreglo se pasa a una función como puntero, el parámetro ocupa un solo slot en el frame. Antes de indexar se hace ldr x10, [x29, #offset] para cargar la dirección base desde el slot, en lugar de calcularla con add.')}
          </SubSection>
          <ImagePlaceholder label="Diagrama: layout en memoria de un arreglo 2D [3][4] en el stack frame" />
        </Section>

        {/* 8. Cadenas */}
        <Section id="mt-cadenas" title="8. Manejo de Cadenas">
          {p('Las cadenas se almacenan como literales ASCII terminados en \\0 en la sección .data. La convención en registros es: x0 = puntero a bytes, x1 = longitud. Los literales de string se emiten con etiquetas únicas _s0, _s1, … usando adrp/add para cargar la dirección en posición independiente (PIC).')}
          <CodeBlock lang="asm" code={`// fmt.Println("Hola")
adrp    x0, _s0
add     x0, x0, :lo12:_s0
mov     x1, #4          // len = 4
mov     x2, x1
mov     x1, x0
mov     x0, #1          // fd = stdout
mov     x8, #64         // syscall write
svc     #0`} />
          <SubSection title="Cadena vacía por defecto">
            {p('Las variables string no inicializadas apuntan a __str_empty (.ascii "\\0"). La función __strlen_setx1 calculará correctamente x1=0 para este puntero.')}
          </SubSection>
        </Section>

        {/* 9. Funciones embebidas */}
        <Section id="mt-embebidas" title="9. Funciones Embebidas">
          <ul className="list-disc space-y-2 pl-5 text-sm text-[var(--ink)]">
            <li><strong>fmt.Println / fmt.Print</strong> — emite cada argumento con el helper de tipo correspondiente; Println añade \\n al final.</li>
            <li><strong>len(arr)</strong> — para arreglos: product(dims) calculado en compile-time; para strings: usa x1 si ya fue calculado, o llama __strlen_setx1.</li>
            <li><strong>typeOf(expr)</strong> — evalúa la expresión solo para inferir su tipo Golampi; devuelve un literal de texto estático (p. ej. "int32").</li>
            <li><strong>now()</strong> — delega en __now, que usa la syscall clock_gettime para obtener segundos Unix y los convierte a "YYYY-MM-DD HH:MM:SS".</li>
            <li><strong>substr(s, start, len)</strong> — aritmética de puntero: x0 += start, x1 = len. No copia los bytes; devuelve una vista del string original.</li>
          </ul>
        </Section>

        {/* 10. Pipeline de prueba */}
        <Section id="mt-pruebas" title="10. Pipeline de Prueba">
          {p('El flujo completo para compilar y ejecutar un archivo de prueba desde la línea de comandos:')}
          <CodeBlock lang="bash" code={`cd BackEnd
php compilar.php "test/archivos prueba/archivoN.go" > /tmp/t.s
aarch64-linux-gnu-as /tmp/t.s -o /tmp/t.o
aarch64-linux-gnu-ld /tmp/t.o -o /tmp/t
qemu-aarch64 /tmp/t`} />
          <SubSection title="Archivos de prueba y cobertura">
            <div className="overflow-auto rounded-xl border border-[var(--line)]">
              <table className="report-table min-w-full border-collapse">
                <thead>
                  <tr>
                    <th>Archivo</th>
                    <th>Contenido</th>
                    <th>Estado</th>
                  </tr>
                </thead>
                <tbody>
                  {[
                    ['archivo1_basico.go', 'Variables, aritmética, tipos, fmt', '✅ 100%'],
                    ['archivo2_intermedio.go', 'If/else, for, switch/case/break/continue', '✅ 100%'],
                    ['archivo3_funciones.go', 'Funciones + embebidas (len, now, substr, typeOf)', '✅ 100%'],
                    ['archivo4_arreglos1d.go', 'Arreglos 1D y 2D básico', '✅ 100%'],
                    ['archivo5_arreglos_ndim.go', 'Matrices, cubos, funciones con arreglos', '✅ 100%'],
                    ['archivo6_avanzado.go', 'Integración completa', '✅ 100%'],
                  ].map(([file, desc, status]) => (
                    <tr key={file}>
                      <td><code>{file}</code></td>
                      <td>{desc}</td>
                      <td>{status}</td>
                    </tr>
                  ))}
                </tbody>
              </table>
            </div>
          </SubSection>
          <ImagePlaceholder label="Captura: salida de qemu-aarch64 con los 6 archivos de prueba pasando" />
        </Section>

      </section>
    </article>
  )
}

export default ManualTecnico