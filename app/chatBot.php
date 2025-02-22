<?php

// Obtener el mensaje del usuario
$userMessage = strtolower($_GET['message']);

// Respuestas predefinidas
$responses = array(
    "Hola" => "Hola, en que puedo ayudarte.",
    "que requisitos se necesitan para realizar las practicas" => "Debes de haber realizado las vinculaciones y estar entre 4 y 5 semestre",
    "¿Cuánto tiempo duran las prácticas preprofesionales?" => "Las prácticas preprofesionales tienen un tiempo de 3 meses.",
    "¿Recibire paga al estar realizando las practicas?" => "La remuneración en las prácticas preprofesionales puede variar. Algunas son remuneradas, mientras que otras pueden ofrecer compensación no monetaria, como experiencia y créditos académicos.",
    "¿Se puede hacer prácticas preprofesionales en el extranjero?" => "Sí, muchas empresas y programas ofrecen oportunidades de prácticas preprofesionales internacionales. Esto puede proporcionar una experiencia única y enriquecedora.",
    "¿Cómo prepararse para una entrevista de prácticas preprofesionales?" => "Prepárate investigando sobre la empresa, practicando respuestas a preguntas comunes y destacando tus habilidades y logros relevantes.",
    "¿Las prácticas preprofesionales garantizan un empleo después de completarlas?" => "No hay garantía, pero las prácticas pueden aumentar tus posibilidades de empleo al proporcionar experiencia y establecer conexiones profesionales.",
    "¿Puedo realizar prácticas preprofesionales en más de una empresa?" => "Sí, en muchos casos, puedes realizar prácticas en diferentes empresas para obtener una variedad de experiencias y habilidades.",
    "¿Qué beneficios adicionales puedo obtener de las prácticas preprofesionales?" => "Además de la experiencia laboral, las prácticas pueden ofrecer mentoría, desarrollo de habilidades específicas y la oportunidad de explorar y confirmar tu elección de carrera.",
    "¿Hay limitaciones en cuanto a la edad para realizar prácticas preprofesionales?" => "En general, no hay limitaciones de edad para realizar prácticas preprofesionales. La mayoría de las oportunidades se centran en la etapa académica y no en la edad del estudiante.",
    "¿Cómo puedo destacar en mis prácticas preprofesionales?" => "Destaca siendo proactivo, mostrando iniciativa, demostrando habilidades relevantes y buscando oportunidades para aprender y contribuir al equipo.",
    "¿Es posible convertir unas prácticas preprofesionales en un empleo a tiempo completo?" => "Sí, muchas empresas consideran la posibilidad de contratar a sus pasantes después de que completan sus estudios si han demostrado un desempeño excepcional durante las prácticas."
    // Agrega más respuestas según sea necesario
);

// Buscar la respuesta correspondiente
$botResponse = "Lo siento, no entendí tu pregunta. ¿Puedes ser más específico?";
foreach ($responses as $question => $answer) {
    similar_text($userMessage, $question, $percentage);

    // Establecer un umbral para considerar una coincidencia
    if ($percentage > 70) {
        $botResponse = $answer;
        break;
    }
}

// Enviar la respuesta al usuario en un formato JSON para manejar en el lado del cliente
$responseArray = array(
    "userMessage" => $userMessage,
    "botResponse" => $botResponse,
);

// Enviar la respuesta al usuario
echo $botResponse;
?>