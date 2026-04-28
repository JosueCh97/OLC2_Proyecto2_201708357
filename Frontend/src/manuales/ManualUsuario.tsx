function ManualUsuario() {
  return (
    <article className="rounded-2xl border border-[var(--line)] bg-[var(--panel)] p-5 md:p-7">
      <header className="mb-5 border-b border-[var(--line)] pb-4">
        <h2 className="m-0 text-2xl font-semibold text-[var(--ink)]">
          Manual de Usuario: Interprete Golampi
        </h2>
        <p className="mt-2 text-sm text-[var(--muted)]">
          Guia practica para redactar, ejecutar y revisar programas desde la interfaz.
        </p>
      </header>

      <section className="mb-6">
        <h3 className="m-0 text-sm font-semibold uppercase tracking-[0.12em] text-[var(--muted)]">
          Indice
        </h3>
        <ol className="mt-3 list-decimal space-y-1 pl-5 text-sm text-[var(--ink)]">
          <li>Funcionamiento General</li>
          <li>Interfaz Grafica y Botones</li>
          <li>Escritura de Codigo (Sintaxis y Estructuras)</li>
          <li>Reportes y Descargas</li>
        </ol>
      </section>

      <section className="space-y-4">
        <details open className="rounded-xl border border-[var(--line)] bg-[var(--card)] p-4">
          <summary className="cursor-pointer text-base font-semibold text-[var(--ink)]">
            1. Funcionamiento General
          </summary>
          <p className="mt-3 text-sm leading-relaxed text-[var(--ink)]">
            El interprete Golampi permite redactar, analizar y ejecutar codigo bajo una
            sintaxis inspirada en Golang. Al presionar Ejecutar, el texto se envia al backend,
            donde se realiza analisis lexico, sintactico y semantico. Luego se devuelve la
            salida de consola y los errores detectados.
          </p>
        </details>

        <details open className="rounded-xl border border-[var(--line)] bg-[var(--card)] p-4">
          <summary className="cursor-pointer text-base font-semibold text-[var(--ink)]">
            2. Interfaz Grafica y Botones
          </summary>
          <ul className="mt-3 list-disc space-y-1 pl-5 text-sm leading-relaxed text-[var(--ink)]">
            <li>Cargar Archivo: Inserta automaticamente el contenido en el editor.</li>
            <li>Guardar Archivo: Descarga el texto actual del editor.</li>
            <li>Ejecutar: Envia el codigo al backend para analizarlo.</li>
            <li>Limpiar Editor: Borra el contenido del area de codigo.</li>
            <li>Limpiar Consola: Borra los mensajes mostrados en consola.</li>
            <li>Consola de Salida: Muestra impresiones y errores detallados.</li>
          </ul>
        </details>

        <details open className="rounded-xl border border-[var(--line)] bg-[var(--card)] p-4">
          <summary className="cursor-pointer text-base font-semibold text-[var(--ink)]">
            3. Escritura de Codigo (Sintaxis y Estructuras)
          </summary>

          <p className="mt-3 text-sm leading-relaxed text-[var(--ink)]">
            Todo programa requiere una funcion principal llamada main. Desde ahi, las
            instrucciones se agrupan en bloques delimitados con llaves.
          </p>

          <details open className="mt-3 rounded-lg border border-[var(--line)] bg-[var(--panel)] p-3">
            <summary className="cursor-pointer text-sm font-semibold text-[var(--ink)]">
              Tipos de Datos y nil
            </summary>
            <ul className="mt-2 list-disc space-y-1 pl-5 text-sm text-[var(--ink)]">
              <li>int32: Enteros con signo. Valor por defecto: 0.</li>
              <li>float32: Decimales. Valor por defecto: 0.0.</li>
              <li>bool: true o false. Valor por defecto: false.</li>
              <li>rune: Un caracter en comillas simples. Valor por defecto: 0.</li>
              <li>string: Texto en comillas dobles.</li>
              <li>nil: Ausencia de valor. Operaciones matematicas con nil generan error.</li>
            </ul>
          </details>

          <details open className="mt-3 rounded-lg border border-[var(--line)] bg-[var(--panel)] p-3">
            <summary className="cursor-pointer text-sm font-semibold text-[var(--ink)]">
              Variables y Constantes
            </summary>
            <p className="mt-2 text-sm text-[var(--ink)]">
              Las variables tienen alcance lexico. Las constantes deben inicializarse al
              declararse y no pueden modificarse.
            </p>
            <pre className="mt-2 overflow-auto rounded-lg border border-[var(--line)] bg-[#111922] p-3 text-xs text-[#d7f2ff]">
{`var x int32 = 10
var y float32
var w, z int32 = 1, 2

a, b := 34, 68

const pi float32 = 3.14`}
            </pre>
          </details>

          <details open className="mt-3 rounded-lg border border-[var(--line)] bg-[var(--panel)] p-3">
            <summary className="cursor-pointer text-sm font-semibold text-[var(--ink)]">
              Operadores
            </summary>
            <ul className="mt-2 list-disc space-y-1 pl-5 text-sm text-[var(--ink)]">
              <li>Aritmeticos: +, -, *, /, %.</li>
              <li>Asignacion: =, +=, -=, *=, /=, %=.</li>
              <li>Relacionales: ==, !=, &gt;, &gt;=, &lt;, &lt;=.</li>
              <li>Logicos: &&, ||, !.</li>
              <li>
                Cortocircuito: en a && b, si a es falso no se evalua b. En a || b, si a es
                verdadero no se evalua b.
              </li>
            </ul>
          </details>

          <details open className="mt-3 rounded-lg border border-[var(--line)] bg-[var(--panel)] p-3">
            <summary className="cursor-pointer text-sm font-semibold text-[var(--ink)]">
              Control de Flujo (if, switch, for)
            </summary>
            <pre className="mt-2 overflow-auto rounded-lg border border-[var(--line)] bg-[#111922] p-3 text-xs text-[#d7f2ff]">
{`if x > 0 {
  fmt.Println("Positivo")
} else if x == 0 {
  fmt.Println("Cero")
} else {
  fmt.Println("Negativo")
}

switch dia {
  case 1:
    fmt.Println("Lunes")
  case 2, 3:
    fmt.Println("Martes o Miercoles")
  default:
    fmt.Println("Otro dia")
}

for i := 0; i < 5; i++ {
  fmt.Println(i)
}`}
            </pre>
            <p className="mt-2 text-sm text-[var(--ink)]">
              Puedes usar break para salir del ciclo y continue para saltar a la siguiente
              iteracion.
            </p>
          </details>

          <details open className="mt-3 rounded-lg border border-[var(--line)] bg-[var(--panel)] p-3">
            <summary className="cursor-pointer text-sm font-semibold text-[var(--ink)]">
              Arreglos
            </summary>
            <pre className="mt-2 overflow-auto rounded-lg border border-[var(--line)] bg-[#111922] p-3 text-xs text-[#d7f2ff]">
{`var nums [5]int32
nums[0] = 10

var letras [3]rune = [3]rune{'a', 'b', 'c'}

var matriz [2][2]int32 = [2][2]int32{
  {1, 2},
  {3, 4},
}`}
            </pre>
          </details>

          <details open className="mt-3 rounded-lg border border-[var(--line)] bg-[var(--panel)] p-3">
            <summary className="cursor-pointer text-sm font-semibold text-[var(--ink)]">
              Funciones y Punteros
            </summary>
            <p className="mt-2 text-sm text-[var(--ink)]">
              Se permiten funciones con multiples retornos y paso por referencia mediante & y *.
            </p>
            <pre className="mt-2 overflow-auto rounded-lg border border-[var(--line)] bg-[#111922] p-3 text-xs text-[#d7f2ff]">
{`func operar(a int32, b int32) (int32, int32) {
  return a + b, a * b
}

func modificar(a *[3]int32) {
  (*a)[0] = 99
}

func main() {
  var arreglo [3]int32 = [3]int32{1, 2, 3}
  modificar(&arreglo)
  fmt.Println(arreglo[0])
}`}
            </pre>
          </details>

          <details open className="mt-3 rounded-lg border border-[var(--line)] bg-[var(--panel)] p-3">
            <summary className="cursor-pointer text-sm font-semibold text-[var(--ink)]">
              Funciones Embebidas
            </summary>
            <ul className="mt-2 list-disc space-y-1 pl-5 text-sm text-[var(--ink)]">
              <li>fmt.Println(a, b): Imprime con salto de linea.</li>
              <li>len(elemento): Devuelve longitud de string o arreglo.</li>
              <li>now(): Retorna fecha y hora actual.</li>
              <li>substr(cadena, inicio, fin): Extrae un fragmento de texto.</li>
              <li>typeOf(variable): Devuelve el tipo como texto.</li>
            </ul>
          </details>
        </details>

        <details open className="rounded-xl border border-[var(--line)] bg-[var(--card)] p-4">
          <summary className="cursor-pointer text-base font-semibold text-[var(--ink)]">
            4. Reportes y Descargas
          </summary>
          <ul className="mt-3 list-disc space-y-1 pl-5 text-sm text-[var(--ink)]">
            <li>
              Reporte de Errores: lista de errores lexico, sintactico y semantico con linea,
              columna y descripcion.
            </li>
            <li>
              Tabla de Simbolos: variables y funciones detectadas, con tipo, entorno y posicion.
            </li>
            <li>
              Tokens y salidas: se pueden exportar para revisar el analisis y documentar pruebas.
            </li>
          </ul>
        </details>
      </section>
    </article>
  )
}

export default ManualUsuario
