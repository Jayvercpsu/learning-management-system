<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Quiz Result - {{ $attempt->quiz->title }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 40px;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 3px solid #667eea;
        }
        .header h1 {
            color: #667eea;
            margin: 0;
        }
        .info-box {
            background: #f8f9fa;
            padding: 20px;
            margin-bottom: 30px;
            border-radius: 5px;
        }
        .info-box table {
            width: 100%;
        }
        .info-box td {
            padding: 8px;
        }
        .question {
            margin-bottom: 30px;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .question-header {
            font-weight: bold;
            font-size: 18px;
            margin-bottom: 10px;
            color: #667eea;
        }
        .correct {
            color: green;
            font-weight: bold;
        }
        .incorrect {
            color: red;
            font-weight: bold;
        }
        .answer-box {
            background: #f8f9fa;
            padding: 10px;
            margin: 10px 0;
            border-left: 4px solid #667eea;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Quiz Result Certificate</h1>
        <h3>{{ $attempt->quiz->title }}</h3>
    </div>

    <div class="info-box">
        <table>
            <tr>
                <td><strong>Student Name:</strong></td>
                <td>{{ $attempt->user->name }}</td>
                <td><strong>Date:</strong></td>
                <td>{{ $attempt->submitted_at->format('M d, Y') }}</td>
            </tr>
            <tr>
                <td><strong>Score:</strong></td>
                <td>{{ number_format($attempt->score, 2) }}%</td>
                <td><strong>Points:</strong></td>
                <td>{{ $attempt->earned_points }}/{{ $attempt->total_points }}</td>
            </tr>
            <tr>
                <td><strong>Status:</strong></td>
                <td colspan="3">
                    @if($attempt->score >= $attempt->quiz->passing_score)
                        <span class="correct">PASSED</span>
                    @else
                        <span class="incorrect">FAILED</span>
                    @endif
                    (Passing Score: {{ $attempt->quiz->passing_score }}%)
                </td>
            </tr>
        </table>
    </div>

    <h2>Detailed Results</h2>

    @foreach($attempt->answers as $index => $answer)
        <div class="question">
            <div class="question-header">
                Question {{ $index + 1 }}
                <span style="float: right;">
                    @if($answer->is_correct)
                        <span class="correct">✓ Correct</span>
                    @elseif($answer->is_correct === false)
                        <span class="incorrect">✗ Incorrect</span>
                    @endif
                    ({{ $answer->points_earned }}/{{ $answer->question->points }} points)
                </span>
            </div>

            <p><strong>{{ $answer->question->question }}</strong></p>

            <div class="answer-box">
                <strong>Your Answer:</strong><br>
                {{ $answer->answer }}
            </div>

            @if($answer->question->type !== 'essay' && $answer->question->correct_answer)
                <div class="answer-box" style="border-left-color: green;">
                    <strong>Correct Answer:</strong><br>
                    {{ $answer->question->correct_answer }}
                </div>
            @endif
        </div>
    @endforeach

    <div style="text-align: center; margin-top: 50px; padding-top: 20px; border-top: 1px solid #ddd;">
        <p>This is an automatically generated certificate from the Learning Management System</p>
        <p>Generated on {{ now()->format('F d, Y \a\t H:i') }}</p>
    </div>
</body>
</html>
