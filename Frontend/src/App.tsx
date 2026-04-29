import { useEffect, useMemo, useRef, useState, type ChangeEvent } from 'react'
import './App.css'
import ManualUsuario from './manuales/ManualUsuario'
import ManualTecnico from './manuales/ManualTecnico'

type AnalisisRespuesta = {
  consola?: string
  codigoARM64?: string
  erroresGenerador?: string[]
  tokens?: unknown[]
  errores?: unknown[]
  erroresLexicos?: unknown[]
  erroresSintacticos?: unknown[]
  erroresSemanticos?: unknown[]
  simbolos?: unknown[]
}

type Pestana = 'compilador' | 'reportes' | 'manual'
type ManualActivo = 'usuario' | 'tecnico'
type SeccionReporte =
  | 'general'
  | 'tokens'
  | 'erroresLexicos'
  | 'erroresSintacticos'
  | 'erroresSemanticos'
  | 'simbolos'

type SocialKind = 'github' | 'linkedin'
type CompileStatus = null | 'ok' | 'warn' | 'error'

const API_URL =
  (import.meta.env.VITE_API_URL as string | undefined) ?? '/api/analizar.php'

const GITHUB_URL = ''
const LINKEDIN_URL = ''
const AUTHOR_NAME = 'Josue Chilel-201708357'

function SocialIcon({ kind }: { kind: SocialKind }) {
  if (kind === 'github') {
    return (
      <svg viewBox="0 0 24 24" aria-hidden="true" focusable="false">
        <path d="M12 .5C5.7.5.6 5.7.6 12.1c0 5.1 3.3 9.4 7.9 10.9.6.1.8-.2.8-.5v-1.9c-3.2.7-3.8-1.4-3.8-1.4-.5-1.2-1.1-1.6-1.1-1.6-.9-.6.1-.6.1-.6 1 .1 1.6 1.1 1.6 1.1.9 1.6 2.5 1.1 3.1.8.1-.7.4-1.1.7-1.4-2.5-.3-5.1-1.3-5.1-5.8 0-1.3.5-2.4 1.2-3.2-.1-.3-.5-1.5.1-3 0 0 1-.3 3.3 1.2.9-.2 1.9-.3 2.8-.3s1.9.1 2.8.3c2.2-1.5 3.3-1.2 3.3-1.2.6 1.5.2 2.7.1 3 .8.8 1.2 1.8 1.2 3.2 0 4.5-2.6 5.5-5.2 5.8.4.4.8 1.1.8 2.2v3.2c0 .3.2.6.8.5 4.6-1.5 7.9-5.8 7.9-10.9C23.4 5.7 18.3.5 12 .5z" />
      </svg>
    )
  }
  return (
    <svg viewBox="0 0 24 24" aria-hidden="true" focusable="false">
      <path d="M20.5 20.5h-3.4v-5.3c0-1.3-.1-3-1.9-3-1.9 0-2.2 1.5-2.2 2.9v5.4H9.6V9.4h3.2v1.5h.1c.4-.8 1.6-1.8 3.3-1.8 3.5 0 4.1 2.3 4.1 5.3v6.1zM5.3 7.8a2 2 0 1 1 0-4 2 2 0 0 1 0 4zm1.7 12.7H3.6V9.4H7v11.1zM22.2 0H1.8C.8 0 0 .8 0 1.8v20.4C0 23.2.8 24 1.8 24h20.4c1 0 1.8-.8 1.8-1.8V1.8C24 .8 23.2 0 22.2 0z" />
    </svg>
  )
}

function CopyIcon() {
  return (
    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round" aria-hidden="true">
      <rect x="9" y="9" width="13" height="13" rx="2" ry="2" />
      <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1" />
    </svg>
  )
}

function DownloadIcon() {
  return (
    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2.2" strokeLinecap="round" strokeLinejoin="round" aria-hidden="true">
      <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
      <path d="M7 10l5 5 5-5" />
      <path d="M12 15V3" />
    </svg>
  )
}

function CheckIcon() {
  return (
    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2.5" strokeLinecap="round" strokeLinejoin="round" aria-hidden="true">
      <polyline points="20 6 9 17 4 12" />
    </svg>
  )
}

function useCopyToClipboard(resetMs = 2000) {
  const [copied, setCopied] = useState(false)
  const timer = useRef<ReturnType<typeof setTimeout> | null>(null)

  const copy = (text: string) => {
    navigator.clipboard.writeText(text).then(() => {
      setCopied(true)
      if (timer.current) clearTimeout(timer.current)
      timer.current = setTimeout(() => setCopied(false), resetMs)
    })
  }
  return { copied, copy }
}

function App() {
  const [pestana, setPestana] = useState<Pestana>('compilador')
  const [codigo, setCodigo] = useState('')
  const [consola, setConsola] = useState('')
  const [codigoARM64, setCodigoARM64] = useState('')
  const [isRunning, setIsRunning] = useState(false)
  const [compileStatus, setCompileStatus] = useState<CompileStatus>(null)
  const [ultimaRespuesta, setUltimaRespuesta] = useState<AnalisisRespuesta | null>(null)
  const [ultimoNombreArchivo, setUltimoNombreArchivo] = useState('entrada.gol')
  const [seccionReporte, setSeccionReporte] = useState<SeccionReporte>('general')
  const [manualActivo, setManualActivo] = useState<ManualActivo>('usuario')

  const fileInputRef = useRef<HTMLInputElement | null>(null)
  const consolaSectionRef = useRef<HTMLElement | null>(null)
  const { copied: copiedARM64, copy: copyARM64 } = useCopyToClipboard()
  const { copied: copiedEditor, copy: copyEditor } = useCopyToClipboard()

  const huboEjecucion = ultimaRespuesta !== null

  const erroresLexicos = useMemo(
    () => (Array.isArray(ultimaRespuesta?.erroresLexicos) ? ultimaRespuesta.erroresLexicos : []),
    [ultimaRespuesta],
  )
  const erroresSintacticos = useMemo(
    () => (Array.isArray(ultimaRespuesta?.erroresSintacticos) ? ultimaRespuesta.erroresSintacticos : []),
    [ultimaRespuesta],
  )
  const erroresSemanticos = useMemo(
    () => (Array.isArray(ultimaRespuesta?.erroresSemanticos) ? ultimaRespuesta.erroresSemanticos : []),
    [ultimaRespuesta],
  )
  const simbolos = useMemo(
    () => (Array.isArray(ultimaRespuesta?.simbolos) ? ultimaRespuesta.simbolos : []),
    [ultimaRespuesta],
  )
  const tokens = useMemo(
    () => (Array.isArray(ultimaRespuesta?.tokens) ? ultimaRespuesta.tokens : []),
    [ultimaRespuesta],
  )

  const erroresCombinados = useMemo(() => {
    if (!ultimaRespuesta) return []
    if (Array.isArray(ultimaRespuesta.errores) && ultimaRespuesta.errores.length > 0)
      return ultimaRespuesta.errores
    return [...erroresLexicos, ...erroresSintacticos, ...erroresSemanticos]
  }, [ultimaRespuesta, erroresLexicos, erroresSintacticos, erroresSemanticos])

  const arm64Lines = useMemo(
    () => (codigoARM64 ? codigoARM64.split('\n').length : 0),
    [codigoARM64],
  )

  // Ctrl+Enter → compilar (salvo que el foco esté en un input de archivo)
  useEffect(() => {
    const handler = (e: KeyboardEvent) => {
      if ((e.ctrlKey || e.metaKey) && e.key === 'Enter') {
        const tag = (e.target as HTMLElement).tagName.toLowerCase()
        if (tag !== 'input') {
          e.preventDefault()
          ejecutarAnalisis()
        }
      }
    }
    window.addEventListener('keydown', handler)
    return () => window.removeEventListener('keydown', handler)
  })

  const setLineaConsola = (linea: string) => {
    setConsola((prev) => (prev ? `${prev}\n${linea}` : linea))
  }

  const normalizarConsola = (data: unknown): string => {
    if (!data || typeof data !== 'object') return 'Respuesta invalida del servidor.'
    const res = data as AnalisisRespuesta
    if (typeof res.consola === 'string' && res.consola.length > 0) return res.consola
    return 'Analisis completado sin salida de consola.'
  }

  const onCargarArchivo = async (event: ChangeEvent<HTMLInputElement>) => {
    const file = event.target.files?.[0]
    if (!file) return
    setUltimoNombreArchivo(file.name)
    const text = await file.text()
    setCodigo(text)
    setLineaConsola(`Archivo cargado: ${file.name}`)
  }

  const descargarTexto = (nombre: string, contenido: string, mimeType = 'text/plain') => {
    const blob = new Blob([contenido], { type: `${mimeType};charset=utf-8` })
    const url = URL.createObjectURL(blob)
    const a = document.createElement('a')
    a.href = url
    a.download = nombre
    a.click()
    URL.revokeObjectURL(url)
  }

  const guardarEditor = () => {
    const nombre =
      ultimoNombreArchivo.endsWith('.txt') || ultimoNombreArchivo.endsWith('.gol')
        ? ultimoNombreArchivo
        : `${ultimoNombreArchivo}.gol`
    descargarTexto(nombre, codigo || '// Archivo vacio')
    setLineaConsola(`Archivo guardado: ${nombre}`)
  }

  const limpiarEditorYConsola = () => {
    setCodigo('')
    setConsola('')
    setCodigoARM64('')
    setUltimaRespuesta(null)
    setCompileStatus(null)
    if (fileInputRef.current) fileInputRef.current.value = ''
  }

  const limpiarConsola = () => setConsola('')

  const ejecutarAnalisis = async () => {
    if (!codigo.trim()) {
      setLineaConsola('No hay codigo para analizar.')
      return
    }
    setPestana('compilador')
    setIsRunning(true)
    setCompileStatus(null)
    setLineaConsola('Iniciando analisis...')

    try {
      const response = await fetch(API_URL.trim(), {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ codigo }),
      })
      if (!response.ok) throw new Error(`HTTP ${response.status}`)

      const data = (await response.json()) as AnalisisRespuesta
      setUltimaRespuesta(data)
      setConsola(normalizarConsola(data))
      setCodigoARM64(typeof data.codigoARM64 === 'string' ? data.codigoARM64 : '')
      setSeccionReporte('general')

      const totalErr =
        (Array.isArray(data.erroresLexicos) ? data.erroresLexicos.length : 0) +
        (Array.isArray(data.erroresSintacticos) ? data.erroresSintacticos.length : 0) +
        (Array.isArray(data.erroresSemanticos) ? data.erroresSemanticos.length : 0)

      setCompileStatus(totalErr === 0 ? 'ok' : totalErr <= 3 ? 'warn' : 'error')

      requestAnimationFrame(() => {
        consolaSectionRef.current?.scrollIntoView({ behavior: 'smooth', block: 'start' })
      })
    } catch (error) {
      const msg = error instanceof Error ? error.message : 'Fallo desconocido'
      setLineaConsola(`Error al conectar con API (${API_URL.trim()}): ${msg}`)
      setCompileStatus('error')
    } finally {
      setIsRunning(false)
    }
  }

  const descargarSalidaConsola = () =>
    descargarTexto('resultado_consola.txt', consola || 'Sin salida de consola')

  const descargarErrores = () => {
    const payload = { total: erroresCombinados.length, errores: erroresCombinados }
    descargarTexto('errores.json', JSON.stringify(payload, null, 2), 'application/json')
  }

  const descargarARM64 = () =>
    descargarTexto('programa.s', codigoARM64 || '# Sin codigo ARM64 generado', 'text/plain')

  const leerCampo = (item: unknown, key: string): unknown => {
    if (!item || typeof item !== 'object') return ''
    return (item as Record<string, unknown>)[key]
  }

  const filasErroresLexicos = useMemo(
    () =>
      erroresLexicos.map((item) => ({
        tipo: String(leerCampo(item, 'tipo') || 'Lexico'),
        descripcion: String(leerCampo(item, 'descripcion') || ''),
        linea: Number(leerCampo(item, 'linea') || 0),
        columna: Number(leerCampo(item, 'columna') || 0),
      })),
    [erroresLexicos],
  )

  const filasErroresSintacticos = useMemo(
    () =>
      erroresSintacticos.map((item) => ({
        tipo: String(leerCampo(item, 'tipo') || 'Sintactico'),
        descripcion: String(leerCampo(item, 'descripcion') || ''),
        linea: Number(leerCampo(item, 'linea') || 0),
        columna: Number(leerCampo(item, 'columna') || 0),
      })),
    [erroresSintacticos],
  )

  const filasErroresSemanticos = useMemo(
    () =>
      erroresSemanticos.map((item) => ({
        tipo: String(leerCampo(item, 'tipo') || 'Semantico'),
        descripcion: String(leerCampo(item, 'descripcion') || ''),
        linea: Number(leerCampo(item, 'linea') || 0),
        columna: Number(leerCampo(item, 'columna') || 0),
      })),
    [erroresSemanticos],
  )

  const filasTokens = useMemo(
    () =>
      tokens.map((item) => ({
        tipo: String(leerCampo(item, 'tipo') || ''),
        lexema: String(leerCampo(item, 'lexema') || ''),
        linea: Number(leerCampo(item, 'linea') || 0),
        columna: Number(leerCampo(item, 'columna') || 0),
        canal: Number(leerCampo(item, 'canal') || 0),
      })),
    [tokens],
  )

  const filasSimbolos = useMemo(
    () =>
      simbolos.map((item) => ({
        id: String(leerCampo(item, 'id') || ''),
        tipoDato: String(leerCampo(item, 'tipoDato') || ''),
        entorno: String(leerCampo(item, 'entorno') || ''),
        linea: Number(leerCampo(item, 'linea') || 0),
        columna: Number(leerCampo(item, 'columna') || 0),
      })),
    [simbolos],
  )

  const resumenActivo = useMemo(() => {
    switch (seccionReporte) {
      case 'tokens':
        return { titulo: 'Resumen de Tokens', descripcion: 'Lista de tokens reconocidos por el analizador lexico.', total: filasTokens.length }
      case 'erroresLexicos':
        return { titulo: 'Errores Lexicos', descripcion: 'Errores por caracteres o patrones invalidos.', total: filasErroresLexicos.length }
      case 'erroresSintacticos':
        return { titulo: 'Errores Sintacticos', descripcion: 'Errores en la estructura o el orden de la gramatica.', total: filasErroresSintacticos.length }
      case 'erroresSemanticos':
        return { titulo: 'Errores Semanticos', descripcion: 'Errores de ejecucion, tipos o reglas del lenguaje.', total: filasErroresSemanticos.length }
      case 'simbolos':
        return { titulo: 'Tabla de Simbolos', descripcion: 'Variables, funciones y entradas registradas en tabla.', total: filasSimbolos.length }
      default:
        return { titulo: 'Resumen General', descripcion: 'Totales globales de la ultima ejecucion.', total: erroresCombinados.length + tokens.length + simbolos.length }
    }
  }, [seccionReporte, filasTokens.length, filasErroresLexicos.length, filasErroresSintacticos.length, filasErroresSemanticos.length, filasSimbolos.length, erroresCombinados.length, tokens.length, simbolos.length])

  const statusLabel = () => {
    if (!huboEjecucion) return null
    if (compileStatus === 'ok') return <span className="status-badge status-ok">✓ Sin errores</span>
    if (compileStatus === 'warn') return <span className="status-badge status-warn">⚠ {erroresCombinados.length} error{erroresCombinados.length !== 1 ? 'es' : ''}</span>
    if (compileStatus === 'error') return <span className="status-badge status-err">✗ {erroresCombinados.length} error{erroresCombinados.length !== 1 ? 'es' : ''}</span>
    return null
  }

  const renderTablaSeccion = () => {
    if (!huboEjecucion) {
      return <p className="text-sm text-[var(--muted)]">Ejecuta un analisis para visualizar detalles.</p>
    }

    const renderCelda = (valor: unknown) => {
      if (valor === undefined || valor === null || valor === '') return '-'
      return String(valor)
    }

    if (seccionReporte === 'general') {
      return (
        <div className="grid gap-3 md:grid-cols-3">
          {[
            { label: 'Tokens', val: tokens.length },
            { label: 'Errores Totales', val: erroresCombinados.length },
            { label: 'Simbolos', val: simbolos.length },
            { label: 'Lexicos', val: erroresLexicos.length },
            { label: 'Sintacticos', val: erroresSintacticos.length },
            { label: 'Semanticos', val: erroresSemanticos.length },
          ].map(({ label, val }) => (
            <article key={label} className="stat-card rounded-xl border border-[var(--line)] bg-[var(--panel)] p-3">
              <p className="m-0 text-xs uppercase tracking-[0.12em] text-[var(--muted)]">{label}</p>
              <p className={`mt-1 text-2xl font-semibold ${val > 0 && label !== 'Tokens' && label !== 'Simbolos' ? 'text-[#f87171]' : 'text-[var(--ink)]'}`}>{val}</p>
            </article>
          ))}
        </div>
      )
    }

    let columnas: string[] = []
    let filas: Array<Record<string, unknown>> = []

    if (seccionReporte === 'tokens') { columnas = ['tipo', 'lexema', 'linea', 'columna', 'canal']; filas = filasTokens }
    else if (seccionReporte === 'erroresLexicos') { columnas = ['tipo', 'descripcion', 'linea', 'columna']; filas = filasErroresLexicos }
    else if (seccionReporte === 'erroresSintacticos') { columnas = ['tipo', 'descripcion', 'linea', 'columna']; filas = filasErroresSintacticos }
    else if (seccionReporte === 'erroresSemanticos') { columnas = ['tipo', 'descripcion', 'linea', 'columna']; filas = filasErroresSemanticos }
    else if (seccionReporte === 'simbolos') { columnas = ['id', 'tipoDato', 'entorno', 'linea', 'columna']; filas = filasSimbolos }

    if (filas.length === 0) {
      return <p className="text-sm text-[var(--muted)]">No hay datos para esta seccion.</p>
    }

    return (
      <div className="overflow-auto rounded-xl border border-[var(--line)]">
        <table className="report-table min-w-full border-collapse">
          <thead>
            <tr>{columnas.map((col) => <th key={col}>{col}</th>)}</tr>
          </thead>
          <tbody>
            {filas.map((fila, idx) => (
              <tr key={`${seccionReporte}-${idx}`}>
                {columnas.map((col) => <td key={`${seccionReporte}-${idx}-${col}`}>{renderCelda(fila[col])}</td>)}
              </tr>
            ))}
          </tbody>
        </table>
      </div>
    )
  }

  return (
    <div className="app-shell">
      <div className="aurora" aria-hidden="true" />

      {/* ── Header ─────────────────────────────────────────────── */}
      <header className="border-b border-[var(--line)] bg-[var(--panel)]/90 backdrop-blur-sm">
        <div className="mx-auto flex max-w-7xl flex-col gap-4 px-4 py-4 md:px-8">
          <div className="flex flex-col items-start justify-between gap-3 md:flex-row md:items-center">
            <div className="surface-card title-card">
              <p className="text-[11px] uppercase tracking-[0.2em] text-[var(--muted)]">
                OLC2 P2 — USAC · {AUTHOR_NAME}
              </p>
              <h1 className="m-0 text-2xl font-semibold text-[var(--ink)] md:text-3xl">
                Compilador Golampi → ARM64
              </h1>
            </div>

            <div className="flex w-full gap-2 md:w-auto">
              {(['compilador', 'reportes', 'manual'] as Pestana[]).map((p) => (
                <button
                  key={p}
                  type="button"
                  onClick={() => setPestana(p)}
                  className={`tab-btn ${pestana === p ? 'tab-btn-on' : ''}`}
                >
                  {p === 'compilador' ? 'Compilador' : p === 'reportes' ? 'Reportes' : 'Manual'}
                </button>
              ))}
            </div>
          </div>

          {/* ── Toolbar ──────────────────────────────────────────── */}
          <div className="surface-card grid gap-3 rounded-xl border border-[var(--line)] bg-[var(--card)] p-3 md:grid-cols-[1fr_auto] md:items-center">
            <div className="flex flex-wrap items-center gap-2">
              <button type="button" className="action-btn" onClick={() => fileInputRef.current?.click()}>
                Cargar
              </button>
              <button type="button" className="action-btn" onClick={guardarEditor}>
                Guardar
              </button>
              <button type="button" className="action-btn" onClick={limpiarEditorYConsola}>
                Limpiar
              </button>
              {statusLabel()}
            </div>
            <span className="hidden text-xs text-[var(--muted)] md:block">{ultimoNombreArchivo}</span>
            <input ref={fileInputRef} type="file" accept=".txt,.gol,.go" className="hidden" onChange={onCargarArchivo} />
          </div>
        </div>
      </header>

      {/* ── Main ───────────────────────────────────────────────── */}
      <main className="mx-auto grid w-full max-w-7xl gap-4 px-4 py-5 md:px-8 md:py-6">

        {/* COMPILADOR ─────────────────────────────────────────── */}
        {pestana === 'compilador' && (
          <section className="grid gap-4 lg:grid-cols-[minmax(0,1.25fr)_minmax(320px,0.9fr)] lg:items-start">
            <div className="grid gap-4">
              <article
                ref={consolaSectionRef}
                className="surface-card rounded-2xl border border-[var(--line)] bg-[var(--panel)] p-3 shadow-sm md:p-4"
              >
                <div className="mb-2 flex items-center justify-between gap-2">
                  <h2 className="m-0 text-sm font-semibold uppercase tracking-[0.16em] text-[var(--muted)]">
                    Editor de Código
                  </h2>
                  <div className="flex items-center gap-1">
                    <span className="text-xs text-[var(--muted)]">{codigo.split('\n').length} líneas</span>
                    <button
                      type="button"
                      className="copy-btn"
                      onClick={() => copyEditor(codigo)}
                      title="Copiar código"
                      disabled={!codigo}
                    >
                      {copiedEditor ? <CheckIcon /> : <CopyIcon />}
                      <span>{copiedEditor ? 'Copiado' : 'Copiar'}</span>
                    </button>
                  </div>
                </div>
                <textarea
                  value={codigo}
                  onChange={(e) => setCodigo(e.target.value)}
                  placeholder="// Escribe o carga tu programa Golampi…"
                  spellCheck={false}
                  className="editor-area min-h-[56vh] w-full resize-y rounded-xl border border-[var(--line)] bg-[var(--card)] p-4 text-sm leading-relaxed text-[var(--ink)] outline-none focus:border-[var(--brand)] lg:min-h-[62vh]"
                />
                <div className="mt-3 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                  <p className="m-0 text-xs text-[var(--muted)]">
                    Presiona <strong>Ctrl + Enter</strong> para compilar
                  </p>
                  <button
                    type="button"
                    className="action-btn action-primary compile-cta"
                    onClick={ejecutarAnalisis}
                    disabled={isRunning}
                    title="Compilar (Ctrl + Enter)"
                  >
                    {isRunning
                      ? <><span className="spinner" aria-hidden="true" /> Compilando…</>
                      : <>Compilar <kbd className="kbd">Ctrl ↵</kbd></>}
                  </button>
                </div>
              </article>

              <article className="surface-card rounded-2xl border border-[var(--line)] bg-[var(--panel)] p-3 shadow-sm md:p-4">
                <div className="mb-2 flex items-center justify-between gap-2">
                  <h2 className="m-0 text-sm font-semibold uppercase tracking-[0.16em] text-[var(--muted)]">
                    Código ARM64
                  </h2>
                  <div className="flex items-center gap-1">
                    {codigoARM64 && (
                      <span className="text-xs text-[var(--muted)]">{arm64Lines} líneas</span>
                    )}
                    <button
                      type="button"
                      className="icon-action-btn"
                      onClick={descargarARM64}
                      disabled={!codigoARM64}
                      title="Descargar ARM64"
                      aria-label="Descargar código ARM64"
                    >
                      <DownloadIcon />
                    </button>
                    <button
                      type="button"
                      className="copy-btn"
                      onClick={() => copyARM64(codigoARM64)}
                      disabled={!codigoARM64}
                      title="Copiar ensamblador"
                    >
                      {copiedARM64 ? <CheckIcon /> : <CopyIcon />}
                      <span>{copiedARM64 ? 'Copiado' : 'Copiar'}</span>
                    </button>
                  </div>
                </div>
                <pre className="console-area arm64-area min-h-[220px] max-h-[44vh] overflow-auto rounded-xl border border-[var(--line)] bg-[#0d1117] p-4 text-xs text-[#79c0ff]">
                  {codigoARM64 || '// Presiona Compilar para generar código ARM64.'}
                </pre>
              </article>
            </div>

            <article className="surface-card rounded-2xl border border-[var(--line)] bg-[var(--panel)] p-3 shadow-sm md:p-4">
              <div className="mb-2 flex items-center justify-between gap-2">
                <h2 className="m-0 text-sm font-semibold uppercase tracking-[0.16em] text-[var(--muted)]">
                  Salida del Intérprete
                </h2>
                <div className="flex items-center gap-1">
                  <button
                    type="button"
                    className="icon-action-btn"
                    onClick={descargarSalidaConsola}
                    disabled={!huboEjecucion}
                    title="Descargar salida del intérprete"
                    aria-label="Descargar salida del intérprete"
                  >
                    <DownloadIcon />
                  </button>
                  <button type="button" className="action-btn" onClick={limpiarConsola}>Limpiar</button>
                </div>
              </div>
              <pre className="console-area min-h-[120px] max-h-[76vh] overflow-auto rounded-xl border border-[var(--line)] bg-[#111922] p-4 text-xs text-[#d7f2ff]">
                {consola || 'Sin salida todavía.'}
              </pre>
            </article>
          </section>
        )}

        {/* REPORTES ───────────────────────────────────────────── */}
        {pestana === 'reportes' && (
          <section className="grid gap-4">
            <article className="surface-card rounded-2xl border border-[var(--line)] bg-[var(--panel)] p-4">
              <h2 className="m-0 text-xl font-semibold text-[var(--ink)]">Panel de Reportes</h2>
              <p className="mt-1 text-sm text-[var(--muted)]">
                Esta sección se habilita después de ejecutar el análisis.
              </p>
              <div className="mt-4 flex flex-wrap gap-2">
                <button type="button" disabled={!huboEjecucion} onClick={descargarSalidaConsola} className="action-btn disabled:cursor-not-allowed disabled:opacity-40">
                  ↓ Salida Intérprete
                </button>
                <button type="button" disabled={!huboEjecucion} onClick={descargarErrores} className="action-btn disabled:cursor-not-allowed disabled:opacity-40">
                  ↓ Errores (.json)
                </button>
                <button type="button" disabled={!codigoARM64} onClick={descargarARM64} className="action-btn action-primary disabled:cursor-not-allowed disabled:opacity-40">
                  ↓ Código ARM64 (.s)
                </button>
              </div>
            </article>

            <article className="surface-card rounded-xl border border-[var(--line)] bg-[var(--card)] p-4">
              <h3 className="text-sm font-semibold uppercase tracking-[0.16em] text-[var(--muted)]">
                Vista de reportes
              </h3>
              <div className="mt-3 flex flex-wrap gap-2">
                {([
                  ['general', 'Resumen General'],
                  ['tokens', 'Tokens'],
                  ['erroresLexicos', 'Léxicos'],
                  ['erroresSintacticos', 'Sintácticos'],
                  ['erroresSemanticos', 'Semánticos'],
                  ['simbolos', 'Símbolos'],
                ] as [SeccionReporte, string][]).map(([key, label]) => (
                  <button
                    key={key}
                    type="button"
                    className={`action-btn ${seccionReporte === key ? 'tab-btn-on' : ''}`}
                    onClick={() => setSeccionReporte(key)}
                  >
                    {label}
                  </button>
                ))}
              </div>

              <div className="mt-4 rounded-xl border border-[var(--line)] bg-[var(--panel)] p-4">
                <h4 className="m-0 text-base font-semibold text-[var(--ink)]">{resumenActivo.titulo}</h4>
                <p className="mt-1 text-sm text-[var(--muted)]">{resumenActivo.descripcion}</p>
                <p className="mt-3 text-sm text-[var(--ink)]">
                  Registros encontrados: <strong>{resumenActivo.total}</strong>
                </p>
              </div>
              <div className="mt-4">{renderTablaSeccion()}</div>
            </article>
          </section>
        )}

        {/* MANUAL ─────────────────────────────────────────────── */}
        {pestana === 'manual' && (
          <section className="grid gap-4">
            <article className="surface-card rounded-2xl border border-[var(--line)] bg-[var(--panel)] p-4">
              <h2 className="m-0 text-xl font-semibold text-[var(--ink)]">Documentación</h2>
              <p className="mt-1 text-sm text-[var(--muted)]">Selecciona el manual que deseas consultar.</p>
              <div className="mt-3 flex flex-wrap gap-2">
                <button type="button" className={`action-btn ${manualActivo === 'usuario' ? 'tab-btn-on' : ''}`} onClick={() => setManualActivo('usuario')}>
                  Manual de Usuario
                </button>
                <button type="button" className={`action-btn ${manualActivo === 'tecnico' ? 'tab-btn-on' : ''}`} onClick={() => setManualActivo('tecnico')}>
                  Manual Técnico
                </button>
              </div>
            </article>

            {manualActivo === 'usuario' && <ManualUsuario />}
            {manualActivo === 'tecnico' && <ManualTecnico />}
          </section>
        )}
      </main>

      {/* ── Footer ─────────────────────────────────────────────── */}
      <footer className="mx-auto mb-4 mt-auto w-full max-w-7xl px-4 md:px-8">
        <div className="surface-card flex flex-col gap-3 rounded-2xl border border-[var(--line)] bg-[var(--panel)] px-4 py-3 text-xs text-[var(--muted)] shadow-sm md:flex-row md:items-center md:justify-between">
          <div>
            <p className="m-0 font-medium text-[var(--ink)]">{AUTHOR_NAME}</p>
            <p className="m-0">Organización de Lenguajes y Compiladores 2 — USAC · 2026</p>
          </div>
          <div className="flex flex-wrap gap-2">
            <a
              href={GITHUB_URL || '#'}
              target="_blank"
              rel="noreferrer"
              className={`social-link ${GITHUB_URL ? '' : 'social-link-empty'}`}
              aria-disabled={!GITHUB_URL}
            >
              <span className="social-icon" aria-hidden="true"><SocialIcon kind="github" /></span>
              <span className="social-link-copy">
                <strong>GitHub</strong>
                <span>Repositorio</span>
              </span>
            </a>
            <a
              href={LINKEDIN_URL || '#'}
              target="_blank"
              rel="noreferrer"
              className={`social-link ${LINKEDIN_URL ? '' : 'social-link-empty'}`}
              aria-disabled={!LINKEDIN_URL}
            >
              <span className="social-icon" aria-hidden="true"><SocialIcon kind="linkedin" /></span>
              <span className="social-link-copy">
                <strong>LinkedIn</strong>
                <span>Perfil</span>
              </span>
            </a>
          </div>
        </div>
      </footer>
    </div>
  )
}

export default App