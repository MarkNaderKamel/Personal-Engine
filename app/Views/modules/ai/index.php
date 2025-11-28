<?php 
$pageTitle = 'AI Assistant'; 
include __DIR__ . '/../../layouts/header.php'; 
use App\Core\Security;
?>

<div class="container mt-4">
    <h2>AI Life Assistant</h2>
    
    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body" style="height: 500px; overflow-y: auto;" id="chatContainer">
                    <?php if (count($history) > 0): ?>
                        <?php foreach (array_reverse($history) as $conv): ?>
                        <div class="mb-3">
                            <div class="p-2 bg-light rounded">
                                <strong>You:</strong> <?= Security::sanitizeOutput($conv['user_message']) ?>
                            </div>
                            <?php if ($conv['ai_response']): ?>
                            <div class="p-2 bg-primary text-white rounded mt-2">
                                <strong>AI:</strong> <?= Security::sanitizeOutput($conv['ai_response']) ?>
                            </div>
                            <?php endif; ?>
                        </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                    <p class="text-muted">Start a conversation with your AI assistant!</p>
                    <?php endif; ?>
                </div>
                <div class="card-footer">
                    <form id="chatForm">
                        <div class="input-group">
                            <input type="text" class="form-control" id="userMessage" 
                                   placeholder="Ask me anything about your life management..." required>
                            <button type="submit" class="btn btn-primary">Send</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('chatForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const message = document.getElementById('userMessage').value;
    const chatContainer = document.getElementById('chatContainer');
    
    const userDiv = document.createElement('div');
    userDiv.className = 'mb-3';
    const userMsgDiv = document.createElement('div');
    userMsgDiv.className = 'p-2 bg-light rounded';
    const userStrong = document.createElement('strong');
    userStrong.textContent = 'You:';
    userMsgDiv.appendChild(userStrong);
    userMsgDiv.appendChild(document.createTextNode(' ' + message));
    userDiv.appendChild(userMsgDiv);
    
    const aiMsgDiv = document.createElement('div');
    aiMsgDiv.className = 'p-2 bg-secondary text-white rounded mt-2';
    const aiStrong = document.createElement('strong');
    aiStrong.textContent = 'AI:';
    aiMsgDiv.appendChild(aiStrong);
    aiMsgDiv.appendChild(document.createTextNode(' Thinking...'));
    userDiv.appendChild(aiMsgDiv);
    
    chatContainer.appendChild(userDiv);
    
    chatContainer.scrollTop = chatContainer.scrollHeight;
    document.getElementById('userMessage').value = '';
    
    try {
        const csrfToken = '<?= \App\Core\Security::generateCSRFToken() ?>';
        const response = await fetch('/ai-assistant/chat', {
            method: 'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: 'message=' + encodeURIComponent(message) + '&csrf_token=' + encodeURIComponent(csrfToken)
        });
        
        const data = await response.json();
        
        const lastAiDiv = userDiv.querySelector('.bg-secondary');
        lastAiDiv.className = 'p-2 bg-primary text-white rounded mt-2';
        while (lastAiDiv.firstChild) {
            lastAiDiv.removeChild(lastAiDiv.firstChild);
        }
        const responseStrong = document.createElement('strong');
        responseStrong.textContent = 'AI:';
        lastAiDiv.appendChild(responseStrong);
        lastAiDiv.appendChild(document.createTextNode(' ' + data.response));
    } catch (error) {
        console.error('Error:', error);
    }
    
    chatContainer.scrollTop = chatContainer.scrollHeight;
});
</script>

<?php include __DIR__ . '/../../layouts/footer.php'; ?>
